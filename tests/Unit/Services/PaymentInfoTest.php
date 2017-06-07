<?php
namespace Tests\Unit\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Http\Client;
use Tests\Helpers\Environment;
use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;
use Tests\TestCase;
use Ebanx\Benjamin\Services\PaymentInfo;

class PaymentInfoTest extends TestCase
{
    private $config;
    private $client;

    public function setup()
    {
        $env = new Environment();
        $text = '{"payment":{"hash":"5925f3653b2c75e9ce7568d4f16c6d67648b8e92f7f05fa5","pin":"484939887","merchant_payment_code":"27c251a65a854cfa74d052e66bdac8e8","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-24 17:56:05","confirm_date":null,"transfer_date":null,"amount_br":"152023.00","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"2355.1200","currency_ext":"USD","due_date":"2017-05-27","instalments":"1","payment_type_code":"baloto","baloto_url":"https:\/\/sandbox.ebanx.com\/print\/baloto\/execute?hash=5925f3653b2c75e9ce7568d4f16c6d67648b8e92f7f05fa5","voucher_id":"484939887","pre_approved":false,"capture_available":null,"note":"Fake payment created by PHPUnit.","customer":{"document":"932221309","email":"alfaro.mara@loya.es.co","name":"LUNA GRANADOS","birth_date":"1966-05-31"}},"status":"SUCCESS"}';

        $this->config = new Config([
            'sandboxIntegrationKey' => $env->read('SANDBOX_INTEGRATION_KEY', 'default_integration_key')
        ]);
        $this->client = new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $text));
    }

    public function testPaymentInfoByHash()
    {
        $query = new PaymentInfoForTests($this->config, $this->client);
        $info = $query->findByHash('5925f3653b2c75e9ce7568d4f16c6d67648b8e92f7f05fa5');

        $this->assertArrayHasKey('payment', $info);
    }

    public function testPaymentInfoByMerchantPaymentCode()
    {
        $query = new PaymentInfoForTests($this->config, $this->client);
        $info = $query->findByMerchantPaymentCode('248b2672f000e293268be28d6048d600');

        $this->assertArrayHasKey('payment', $info);
    }
}

class PaymentInfoForTests extends PaymentInfo
{
    public function __construct(Config $config, Client $client)
    {
        $this->client = $client;
        parent::__construct($config);
    }
}
