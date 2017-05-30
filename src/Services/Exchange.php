<?php
namespace Ebanx\Benjamin\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Http\Client;
use Ebanx\Benjamin\Services\Adapters\ExchangeAdapter;

class Exchange
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Config $config, Client $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    public function siteToLocal($localCurrency, $localValue = 1)
    {
        return $this->fetchRate($this->config->baseCurrency, $localCurrency) * $localValue;
    }

    public function siteToLocalWithTaxes($localCurrency, $localValue = 1)
    {
        return $this->siteToLocal($localCurrency, $localValue) * Config::IOF;
    }

    public function localToSite($localCurrency, $localValue = 1)
    {
        return $this->fetchRate($localCurrency, $this->config->baseCurrency) * $localValue;
    }

    private function fetchRate($fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        $fromIsGlobal = Currency::isGlobal($fromCurrency);
        $toIsGlobal = Currency::isGlobal($toCurrency);

        if (!($fromIsGlobal xor $toIsGlobal)) {
            return 0;
        }

        $adapter = new ExchangeAdapter(
            $fromCurrency,
            $toCurrency,
            $this->config
        );

        $response = $this->client->exchange($adapter->transform());

        if ($response['status'] === Client::ERROR) {
            return 0;
        }

        return $response['currency_rate']['rate'];
    }
}
