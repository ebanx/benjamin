<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;

use Ebanx\Benjamin\Services\Gateways\CreditCard;
use Ebanx\Benjamin\Services\Http\Client;

class CreditCardTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $creditCardConfig = new CreditCardConfig();

        $creditCardSuccessfulResponse = $this->getCreditCardSuccessfulResponseJson();
        $client = $this->getMockedClient($creditCardSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->creditCard()->businessPerson()->build();
        $gateway = new CreditCardForTests($this->config, $creditCardConfig, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSDEUR()
    {
        $creditCardConfig = new CreditCardConfig();
        $gateway = new CreditCard($this->config, $creditCardConfig);

        $this->assertAvailableForCountries($gateway, array(
            Country::BRAZIL,
            Country::MEXICO,
            Country::COLOMBIA
        ));

        $gateway = new CreditCard(new Config(array(
            'baseCurrency' => Currency::EUR
        )), $creditCardConfig);

        $this->assertAvailableForCountries($gateway, array(
            Country::BRAZIL,
            Country::MEXICO,
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $creditCardConfig = new CreditCardConfig();

        $gateway = new CreditCard(new Config(array(
            'baseCurrency' => Currency::BRL
        )), $creditCardConfig);

        $this->assertAvailableForCountries($gateway, array(
            Country::BRAZIL
        ));

        $gateway = new CreditCard(new Config(array(
            'baseCurrency' => Currency::MXN
        )), $creditCardConfig);

        $this->assertAvailableForCountries($gateway, array(
            Country::MEXICO
        ));

        $gateway = new CreditCard(new Config(array(
            'baseCurrency' => Currency::COP
        )), $creditCardConfig);

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $creditCardConfig = new CreditCardConfig();

        $gateway = new CreditCard(new Config(array(
            'baseCurrency' => Currency::CLP
        )), $creditCardConfig);

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testGatewayDefaultMinimumInstalment()
    {
        $usdToBrlRate = 3.4743;
        $client = $this->getMockedClient($this->getExchangeRateSuccessfulResponseJsonWithRate($usdToBrlRate));

        $gateway = new CreditCardForTests($this->config, new CreditCardConfig(), $client);

        $defaultMinInstalment = CreditCardConfig::acquirerMinInstalmentValueForCurrency(Currency::MXN);
        $country = Country::MEXICO;
        $minInstalment = $gateway->getMinInstalmentValueForCountry($country);

        $this->assertEquals($defaultMinInstalment, $minInstalment);

        $gateway = new CreditCardForTests($this->config, new CreditCardConfig([
            'minInstalmentAmount' => $defaultMinInstalment * 1.2
        ]), $client);

        $minInstalment = $gateway->getMinInstalmentValueForCountry($country);

        $this->assertNotEquals($defaultMinInstalment, $minInstalment);
    }

    public function testPaymentTermsForCountryAndValue()
    {
        $usdToBrlRate = 3.4743;
        $client = $this->getMockedClient($this->getExchangeRateSuccessfulResponseJsonWithRate($usdToBrlRate));

        $config = new Config([
            'baseCurrency' => Currency::USD
        ]);

        $creditCardConfig = new CreditCardConfig();
        for ($i = 4; $i <= 6; $i++) {
            $creditCardConfig->addInterest($i, 5);
        }
        for ($i = 7; $i <= 12; $i++) {
            $creditCardConfig->addInterest($i, 10);
        }

        $gateway = new CreditCardForTests($config, $creditCardConfig, $client);
        $country = Country::BRAZIL;

        $value = 50.0;
        // 50.0 (USD) * 3.4743 (Exchange Rate) / 20 (BRL minimum instalment value) * 10% (max interest rate) = 9 instalments

        $paymentTerms = $gateway->getPaymentTermsForCountryAndValue($country, $value);

        $this->assertTrue(is_array($paymentTerms), 'Failed to return array of payment terms');
        $this->assertEquals(9, count($paymentTerms),
            'Wrong number of payment terms');

        for ($i = 0; $i < 3; $i++) {
            $this->assertFalse($paymentTerms[$i]->hasInterests, 'Marked term with interest when it shouldn\'t');
            $this->assertEquals(round($value), round($paymentTerms[$i]->instalmentNumber * $paymentTerms[$i]->baseAmount), 'Failed to add interest to term ' . $paymentTerms[$i]->instalmentNumber);
        }

        $interest = 0.05;
        for ($i = 3; $i < 6; $i++) {
            $this->assertTrue($paymentTerms[$i]->hasInterests, 'Failed to mark term with interest');
            $this->assertEquals(round($value), round($paymentTerms[$i]->instalmentNumber * $paymentTerms[$i]->baseAmount * (1 - $interest)), 'Failed to add interest to term ' . $paymentTerms[$i]->instalmentNumber);
        }

        $interest = 0.1;
        for ($i = 6; $i < 9; $i++) {
            $this->assertTrue($paymentTerms[$i]->hasInterests, 'Failed to mark term '.$i.' with interest');
            $this->assertEquals(round($value), round($paymentTerms[$i]->instalmentNumber * $paymentTerms[$i]->baseAmount * (1 - $interest)), 'Failed to add interest to term ' . $paymentTerms[$i]->instalmentNumber);
        }
    }

    private function getCreditCardSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"591f7a1cae81aaaade3f76014310da4a7289ab651e6ec44e","pin":"440297024","merchant_payment_code":"c1ef11f4be81d3515d2879d486718508","order_number":null,"status":"CA","status_date":"2017-05-19 20:05:00","open_date":"2017-05-19 20:04:59","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2017-05-22","instalments":"1","payment_type_code":"mastercard","transaction_status":{"acquirer":"EBANX","code":"NOK","description":"Sandbox - Not a test card, transaction declined"},"pre_approved":false,"capture_available":false,"note":"Fake payment created by PHPUnit.","customer":{"document":"60639321000162","email":"ksalgado@furtado.org","name":"DR FRANCO MASCARENHAS SOBRINHO","birth_date":"1971-01-07"}},"status":"SUCCESS"}';
    }
}

class CreditCardForTests extends CreditCard
{
    public function __construct(Config $config, CreditCardConfig $creditCardConfig, Client $client)
    {
        $this->client = $client;
        parent::__construct($config, $creditCardConfig);
    }
}
