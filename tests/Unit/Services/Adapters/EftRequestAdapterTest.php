<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\EftRequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class EftRequestAdapterTest extends RequestAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_CO');
        $payment = $factory->payment()->eft()->build();

        $adapter = new EftRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['requestSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }
}
