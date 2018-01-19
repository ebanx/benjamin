<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Models\Address;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Adapters\RequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\TestCase;
use JsonSchema;

class RequestAdapterTest extends PaymentAdapterTest
{
    public function testJsonSchema()
    {
        $factory = new BuilderFactory('pt_BR');
        $request = $factory
            ->request()
            ->build();

        $this->assertModelJsonSchemaCompliance($request);
    }

    public function testJsonSchemaWithSubAccount()
    {
        $factory = new BuilderFactory('pt_BR');
        $request = $factory
            ->request()
            ->withSubAccount()
            ->build();

        $this->assertModelJsonSchemaCompliance($request);
    }

    public function testTransformNotificationUrl()
    {
        $expected = md5(rand(1, 999));

        $nullConfig = new Config();
        $goodConfig = new Config(['notificationUrl' => $expected]);

        $factory = new BuilderFactory('pt_BR');
        $request = $factory->request()->build();

        $adapter = new FakeRequestAdapter($request, $nullConfig);
        $result1 = $adapter->transform();

        $adapter = new FakeRequestAdapter($request, $goodConfig);
        $result2 = $adapter->transform();

        $this->assertEmpty(
            $result1->notification_url,
            'Request adapter injected a notification url when it shouldn\'t'
        );

        $this->assertEquals(
            $expected,
            $result2->notification_url,
            'Request adapter failed to inject a notification url'
        );
    }

    public function testTransformRedirectUrl()
    {
        $expected = 'SAMPLE_URL';

        $factory = new BuilderFactory('pt_BR');
        $request = $factory->request()->build();
        $request->redirectUrl = $expected;

        $adapter = new FakeRequestAdapter($request, new Config());
        $result = $adapter->transform();

        $this->assertEquals(
            $expected,
            $result->redirect_url,
            'Request adapter failed to send redirect_url'
        );
    }

    public function testIntegrationKey()
    {
        $factory = new BuilderFactory('pt_BR');
        $request = $factory->request()->build();

        $liveKey = 'testIntegrationKey';
        $sandboxKey = 'testSandboxIntegrationKey';

        $config = new Config([
            'integrationKey' => $liveKey,
            'sandboxIntegrationKey' => $sandboxKey
        ]);

        // Sandbox
        $adapter = new FakeRequestAdapter($request, $config);
        $this->assertEquals($sandboxKey, $adapter->getIntegrationKey());

        // Live
        $config->isSandbox = false;
        $adapter = new FakeRequestAdapter($request, $config);
        $this->assertEquals($liveKey, $adapter->getIntegrationKey());
    }

    public function testUserValues()
    {
        $factory = new BuilderFactory('pt_BR');
        $request = $factory->request()->build();

        $expected = [
            1 => 'from_tests',
            2 => 'DO NOT PAY',
            5 => 'Benjamin',
        ];

        $request->userValues = [
            1 => 'Override me',
            2 => 'DO NOT PAY',
        ];

        $config = new Config([
            'userValues' => [
                1 => 'from_tests',
            ],
        ]);

        $adapter = new FakeRequestAdapter($request, $config);
        $result = $adapter->transform();

        $resultValues = array_filter([
            1 => isset($result->user_value_1) ? $result->user_value_1 : null,
            2 => isset($result->user_value_2) ? $result->user_value_2 : null,
            3 => isset($result->user_value_3) ? $result->user_value_3 : null,
            4 => isset($result->user_value_4) ? $result->user_value_4 : null,
            5 => isset($result->user_value_5) ? $result->user_value_5 : null,
        ]);

        $this->assertEquals($expected, $resultValues);
    }

    public function testAddress()
    {
        $factory = new BuilderFactory('pt_BR');
        $request = $factory->request()->build();

        $expected = 'Rua Marechal Deodoro';
        $request->address = new Address([
            'address' => $expected,
            'country' => Country::BRAZIL
        ]);

        $adapter = new FakeRequestAdapter($request, new Config());
        $result = $adapter->transform();

        $this->assertEquals($expected, $result->address);
    }

    private function assertModelJsonSchemaCompliance($model)
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);

        $adapter = new FakeRequestAdapter($model, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator();
        $validator->validate($result, $this->getSchema('requestSchema'));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }
}

class FakeRequestAdapter extends RequestAdapter
{
    public function getIntegrationKey()
    {
        return parent::getIntegrationKey();
    }
}
