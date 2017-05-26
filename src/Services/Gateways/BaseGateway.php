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
        $countries = $this->getEnabledCountries();
        $currencies = $this->getEnabledCurrencies();
        $globalCurrencies = Currency::globalCurrencies();
        $localCurrency = Currency::localForCountry($country);

        if (!in_array($country, $countries)
            || !in_array($this->config->baseCurrency, $currencies)
            || (!in_array($this->config->baseCurrency, $globalCurrencies)
                && $this->config->baseCurrency !== $localCurrency)) {
            return false;
        }

        return true;
    }
}
