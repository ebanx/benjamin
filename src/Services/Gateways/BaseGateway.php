<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Http\Client;
use Ebanx\Benjamin\Services\Exchange;

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

    /**
     * @var Exchange
     */
    protected $exchange;

    abstract public function create(Payment $payment);

    abstract protected function getEnabledCountries();
    abstract protected function getEnabledCurrencies();

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = $this->client ?: new Client();

        if (!$this->config->isSandbox) {
            $this->client->inLiveMode();
        }

        $this->exchange = new Exchange($this->config, $this->client);
    }

    public function exchange()
    {
        return $this->exchange;
    }

    public function isAvailableForCountry($country)
    {
        $enabledCountries = $this->getEnabledCountries();
        $siteCurrency = $this->config->baseCurrency;
        $globalCurrencies = Currency::globalCurrencies();
        $localCurrency = Currency::localForCountry($country);

        $countryIsAccepted = in_array($country, $enabledCountries);
        $siteCurrencyIsGlobal = in_array($siteCurrency, $globalCurrencies);
        $siteCurrencyMatchesCountry = $siteCurrency === $localCurrency;

        return $countryIsAccepted
            && $this->isAvailableForCurrency()
            && ($siteCurrencyIsGlobal || $siteCurrencyMatchesCountry);
    }

    public function isAvailableForCurrency($currency = null)
    {
        $siteCurrency = $currency ?: $this->config->baseCurrency;
        $enabledCurrencies = $this->getEnabledCurrencies();

        return in_array($siteCurrency, $enabledCurrencies);
    }

    protected function availableForCountryOrThrow($country)
    {
        if ($this->isAvailableForCountry($country)) {
            return;
        }

        throw new \InvalidArgumentException(sprintf('Gateway not available for %s%s',
            $country,
            Currency::isGlobal($this->config->baseCurrency)
                ? ''
                : ' using '.$this->config->baseCurrency
        ));
    }
}
