<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\PagoEfectivo;
use Ebanx\Benjamin\Services\Http\Client;

class PagoEfectivoTest extends GatewayTestCase
{
    public function testPayment()
    {
        $pagoEfectivoSuccessfulResponse = $this->getPagoEfectivoSuccessfulResponseJson();
        $client = $this->getMockedClient($pagoEfectivoSuccessfulResponse);

        $factory = new BuilderFactory('es_PE');
        $payment = $factory->payment()->build();
        $gateway = new PagoEfectivoForTests($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new PagoEfectivo($this->config);

        $this->assertAvailableForCountries($gateway, array(
            Country::PERU
        ));
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new PagoEfectivo(new Config(array(
            'baseCurrency' => Currency::PEN
        )));

        $this->assertAvailableForCountries($gateway, array(
            Country::PERU
        ));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new PagoEfectivo(new Config(array(
            'baseCurrency' => Currency::BRL
        )));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getPagoEfectivoSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"59260f14654c5e69344fec4cd0e22fe1d9177a5898e3a3d3","pin":"841280229","merchant_payment_code":"5e37ce7c2f03c2dc1e3599e869ddc633","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-24 19:54:11","confirm_date":null,"transfer_date":null,"amount_br":"566.08","amount_ext":"113.21","amount_iof":"0.03","currency_rate":"5.0000","currency_ext":"USD","due_date":"2017-05-23","instalments":"1","payment_type_code":"pagoefectivo","cip_url":"https:\/\/sandbox.ebanx.com\/cip\/?hash=59260f14654c5e69344fec4cd0e22fe1d9177a5898e3a3d3","cip_code":"2829662","pre_approved":false,"capture_available":null,"note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.pe","name":"LUNA GRANADOS","birth_date":"1966-05-19"}},"status":"SUCCESS"}';
    }
}

class PagoEfectivoForTests extends PagoEfectivo
{
    public function __construct(Config $config, Client $client)
    {
        parent::__construct($config);
        $this->client = $client;
    }
}
