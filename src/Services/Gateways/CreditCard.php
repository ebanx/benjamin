<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Services\Adapters\CardRequestAdapter;
use GuzzleHttp\Client;

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

    public function __construct(Config $config, CreditCardConfig $creditCardConfig)
    {
        parent::__construct($config);
        $this->creditCardConfig = $creditCardConfig;
    }

    public function create(Payment $payment)
    {
        $payment->type = "creditCard";

        $adapter = new CardRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->post($request);

        return $body;
    }
}
