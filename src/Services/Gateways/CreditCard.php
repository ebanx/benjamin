<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Responses\PaymentTerm;
use Ebanx\Benjamin\Services\Adapters\CaptureAdapter;
use Ebanx\Benjamin\Services\Adapters\CardRequestAdapter;
use Ebanx\Benjamin\Services\Exchange;
use Ebanx\Benjamin\Services\Http\Client;

class CreditCard extends BaseGateway
{
    protected static function getEnabledCountries()
    {
        return array(
            Country::BRAZIL,
            Country::MEXICO,
            Country::COLOMBIA
        );
    }
    protected static function getEnabledCurrencies()
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

    public function __construct(Config $config, CreditCardConfig $creditCardConfig, Client $client = null)
    {
        parent::__construct($config, $client);
        $this->creditCardConfig = $creditCardConfig;
    }

    protected function getPaymentData(Payment $payment)
    {
        $this->availableForCountryOrThrow($payment->address->country);

        $payment->type = $payment->card->type;

        $adapter = new CardRequestAdapter($payment, $this->config);
        return $adapter->transform();
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

    /**
     * @param string $hash
     * @param float  $amount
     * @param string $merchantCaptureCode
     * @return array
     */
    public function captureByHash($hash, $amount = null, $merchantCaptureCode = null)
    {
        $data = array(
            'hash' => $hash,
            'amount' => $amount,
            'merchantCaptureCode' => $merchantCaptureCode
        );

        $adapter = new CaptureAdapter($data, $this->config);
        $response = $this->client->capture($adapter->transform());

        return $response;
    }

    /**
     * @param string $merchantPaymentCode
     * @param float  $amount
     * @param string $merchantCaptureCode
     * @return array
     */
    public function captureByMerchantPaymentCode($merchantPaymentCode, $amount = null, $merchantCaptureCode = null)
    {
        $data = array(
            'merchantPaymentCode' => $merchantPaymentCode,
            'amount' => $amount,
            'merchantCaptureCode' => $merchantCaptureCode
        );

        $adapter = new CaptureAdapter($data, $this->config);
        $response = $this->client->capture($adapter->transform());

        return $response;
    }

    private function calculatePaymentTerm($instalment, $siteValue, $localValueWithTax, $minimum)
    {
        $interestRates = $this->getInterestRates();

        $interestRatio = 1 + (isset($interestRates[$instalment]) ? $interestRates[$instalment] / 100 : 0);

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

    private function getInterestRates()
    {
        if ($this->interestRates) {
            return $this->interestRates;
        }

        $this->interestRates = array();
        foreach ($this->creditCardConfig->interestRates as $item) {
            $this->interestRates[$item->instalmentNumber] = $item->interestRate;
        }

        return $this->interestRates;
    }
}
