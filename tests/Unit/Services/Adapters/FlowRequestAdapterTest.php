<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\FlowRequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class FlowRequestAdapterTest extends RequestAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->flow()->build();

        $adapter = new FlowRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['requestSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }

    public function testWebpayMethod()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->build();
        $payment->flow_payment_method = 'webpay';

        $adapter = new FlowRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals('webpay', strtolower($result->payment->flow_payment_method));
    }

    public function testOnlineTypeCode()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->build();
        $payment->flow_payment_method = 'multicaja';

        $adapter = new FlowRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals('multicaja', strtolower($result->payment->flow_payment_method));
    }
}
