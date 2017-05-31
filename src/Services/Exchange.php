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

    public function siteToLocal($localCurrency, $siteValue = 1)
    {
        return $this->fetchRate($this->config->baseCurrency, $localCurrency) * $siteValue;
    }

    public function siteToLocalWithTax($localCurrency, $siteValue = 1)
    {
        $taxRatio = 1 + (Currency::BRL === $localCurrency ? Config::IOF : 0.0);

        return $this->siteToLocal($localCurrency, $siteValue) * $taxRatio;
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
