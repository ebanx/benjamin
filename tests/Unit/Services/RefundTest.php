<?php
namespace Tests\Unit\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Http\Client;
use Ebanx\Benjamin\Services\Refund;
use Tests\Helpers\Environment;
use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;
use Tests\TestCase;

class RefundTest extends TestCase
{
    private $config;
    private $client;

    public function setup()
    {
        $env = new Environment();
        $this->config = new Config([
            'sandboxIntegrationKey' => $env->read('SANDBOX_INTEGRATION_KEY', 'default_integration_key')
        ]);

        $text = '{"payment":{"hash":"59386e0a5f258ffed3bee6fecc9150f916d1e19aa50ec68c","pin":"484913390","merchant_payment_code":"145000015-1496870409","order_number":null,"status":"CO","status_date":"2017-06-07 18:20:13","open_date":"2017-06-07 18:20:10","confirm_date":"2017-06-07 18:20:13","transfer_date":null,"amount_br":"265.00","amount_ext":"80.00","amount_iof":"1.00","currency_rate":"3.3000","currency_ext":"USD","due_date":"2017-06-10","instalments":"1","payment_type_code":"itau","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","refunds":[{"id":"21425","merchant_refund_code":null,"status":"RE","request_date":"2017-06-08 17:34:59","pending_date":null,"confirm_date":null,"cancel_date":null,"amount_ext":"10.23","description":"description"},{"id":"21426","merchant_refund_code":null,"status":"RE","request_date":"2017-06-08 17:36:35","pending_date":null,"confirm_date":null,"cancel_date":null,"amount_ext":"10.23","description":"description"}],"customer":{"document":"07834442902","email":"cezar+brbrbrbrbr@ebanx.com","name":"CEZAR LUIZ SAMPAIO","birth_date":"1978-03-29"}},"refund":{"id":"21426","merchant_refund_code":null,"status":"RE","request_date":"2017-06-08 17:36:35","pending_date":null,"confirm_date":null,"cancel_date":null,"amount_ext":"10.23","description":"description"},"operation":"refund","status":"SUCCESS"}';
        $this->client = new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $text));
    }

    public function testRefundByHash()
    {
        $refund = new RefundForTests($this->config, $this->client);
        $result = $refund->requestByHash('59386e0a5f258ffed3bee6fecc9150f916d1e19aa50ec68c', 10.23, 'description');

        $this->assertArrayHasKey('payment', $result);
    }

    public function testRefundByMerchantPaymentCode()
    {
        $refund = new RefundForTests($this->config, $this->client);
        $result = $refund->requestByMerchantPaymentCode('59386e0a5f258ffed3bee6fecc9150f916d1e19aa50ec68c', 10.23, 'description');

        $this->assertArrayHasKey('payment', $result);
    }

    public function testRefundCancel()
    {
        $refund = new RefundForTests($this->config, $this->client);
        $result = $refund->cancel('21425');

        $this->assertArrayHasKey('payment', $result);
    }
}

class RefundForTests extends Refund
{
    public function __construct(Config $config, Client $client)
    {
        $this->client = $client;
        parent::__construct($config);
    }
}
