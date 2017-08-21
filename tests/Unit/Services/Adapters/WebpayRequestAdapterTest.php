<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\WebpayRequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class WebpayRequestAdapterTest extends RequestAdapterTest
{
    public function testJsonSchema()
    {
        $config  = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $adapter = new WebpayRequestAdapter($payment, $config);
        $result  = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema([ 'flowRequestSchema' ]));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }
}
