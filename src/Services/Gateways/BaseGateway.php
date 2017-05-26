<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Http\Client;

abstract class BaseGateway
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    abstract public function create(Payment $payment);

    abstract protected function getEnabledCountries();
    abstract protected function getEnabledCurrencies();

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();

        if (!$this->config->isSandbox) {
            $this->client->inLiveMode();
        }
    }

    public function isAvailableForCountry($country)
    {
        $enabledCountries = $this->getEnabledCountries();
        $enabledCurrencies = $this->getEnabledCurrencies();
        $siteCurrency = $this->config->baseCurrency;
        $globalCurrencies = Currency::globalCurrencies();
        $localCurrency = Currency::localForCountry($country);

        $countryIsValid = in_array($country, $enabledCountries);
        $currencyIsValid = in_array($siteCurrency, $enabledCurrencies);
        $currencyIsGlobal = in_array($siteCurrency, $globalCurrencies);
        $currencyMatchesCountry = $siteCurrency === $localCurrency;

        return $countryIsValid
            && $currencyIsValid
            && ($currencyIsGlobal || $currencyMatchesCountry);
    }
}
