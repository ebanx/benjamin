<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Responses\PaymentTerm;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Services\Adapters\CardRequestAdapter;
use Ebanx\Benjamin\Services\Exchange;

class CreditCard extends BaseGateway
{
    protected function getEnabledCountries()
    {
        return array(
            Country::BRAZIL,
            Country::MEXICO,
            Country::COLOMBIA
        );
    }
    protected function getEnabledCurrencies()
    {
        return array(
            Currency::BRL,
            Currency::MXN,
            Currency::COP,
            Currency::USD,
            Currency::EUR
        );
    }

    private $creditCardConfig;

    private $interestRates;

    public function __construct(Config $config, CreditCardConfig $creditCardConfig)
    {
        parent::__construct($config);
        $this->creditCardConfig = $creditCardConfig;
    }

    public function create(Payment $payment)
    {
        $this->availableForCountryOrThrow($payment->address->country);

        $payment->type = "creditCard";

        $adapter = new CardRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }

    public function getMinInstalmentValueForCountry($country)
    {
        $this->availableForCountryOrThrow($country);

        $localCurrency = Currency::localForCountry($country);
        $acquirerMinimum = CreditCardConfig::acquirerMinInstalmentValueForCurrency($localCurrency);
        $configMinimum = $this->creditCardConfig->minInstalmentAmount;

        return max($acquirerMinimum, $configMinimum);
    }

    public function getPaymentTermsForCountryAndValue($country, $value)
    {
        $this->availableForCountryOrThrow($country);

        $paymentTerms = array();

        $localCurrency = Currency::localForCountry($country);
        $exchange = new Exchange($this->config, $this->client);
        $localValueWithTax = $exchange->siteToLocalWithTax($localCurrency, $value);
        $minInstalment = $this->getMinInstalmentValueForCountry($country);

        // HARD LIMIT
        $maxInstalments = min(CreditCardConfig::MAX_INSTALMENTS, $this->creditCardConfig->maxInstalments);

        for ($i = 1; $i <= $maxInstalments; $i++) {
            $paymentTerms[] = $this->calculatePaymentTerm($i, $value, $localValueWithTax, $minInstalment);
        }

        return array_filter($paymentTerms);
    }

    private function calculatePaymentTerm($instalment, $siteValue, $localValueWithTax, $minimum)
    {
        if (!$this->interestRates) {
            $this->interestRates = array();
            foreach ($this->creditCardConfig->interestRates as $item) {
                $this->interestRates[$item->instalmentNumber] = $item->interestRate;
            }
        }

        $interestRatio = 1 + (isset($this->interestRates[$instalment]) ? $this->interestRates[$instalment] / 100 : 0);

        if ($localValueWithTax / $instalment * $interestRatio < $minimum) {
            return null;
        }

        return new PaymentTerm([
            'instalmentNumber' => $instalment,
            'baseAmount' => ($siteValue / $instalment) * $interestRatio,
            'localAmountWithTax' => ($localValueWithTax / $instalment) * $interestRatio,
            'hasInterests' => $interestRatio > 1
        ]);
    }
}
