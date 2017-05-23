<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Helpers\Mocks\ClientForTests;
use Tests\Helpers\Mocks\EchoEngine;
use Ebanx\Benjamin\Services\Client;

class ClientTest extends TestCase
{
    public function testModeSwitch()
    {
        $subject = new ClientForTests();

        $sandboxMode = $subject->inSandboxMode()->getMode();
        $this->assertTrue($subject->isSandbox());

        $liveMode = $subject->inLiveMode()->getMode();
        $this->assertFalse($subject->isSandbox());

        $this->assertNotEquals($sandboxMode, $liveMode);
        $this->assertEquals($sandboxMode, Client::MODE_SANDBOX);
        $this->assertEquals($liveMode, Client::MODE_LIVE);
    }

    public function testDefaultUrl()
    {
        $subject = new ClientForTests();

        $defaultUrl = $subject->getUrl();
        $this->assertTrue($subject->isSandbox());

        $sandboxUrl = $subject->inSandboxMode()->getUrl();
        $this->assertTrue($subject->isSandbox());

        $this->assertEquals($sandboxUrl, $defaultUrl);
    }

    public function testFakeRequest()
    {
        $text = 'This should be OK';

        $subject = new ClientForTests(new EchoEngine($text));

        $response = $subject->post((object)array('empty'=>true));
        $this->assertEquals($text, $response);
    }

    /** @skip */
    public function testRealRequest()
    {
        $subject = new ClientForTests();

        $response = $subject->post((object)array('empty'=>true));
        $this->assertEquals('ERROR', $response['status']);
    }
}
