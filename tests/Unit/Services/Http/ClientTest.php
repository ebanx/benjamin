<?php
namespace Tests\Unit\Services\Http;

use Tests\TestCase;
use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;
use Ebanx\Benjamin\Services\Http\Client;

class ClientTest extends TestCase
{
    public function testModeSwitch()
    {
        $subject = new Client();

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
        $subject = new Client();

        $defaultUrl = $subject->getUrl();
        $this->assertTrue($subject->isSandbox());

        $sandboxUrl = $subject->inSandboxMode()->getUrl();
        $this->assertTrue($subject->isSandbox());

        $this->assertEquals($sandboxUrl, $defaultUrl);
    }

    public function testLiveUrl()
    {
        $subject = new Client();

        $sandboxUrl = $subject->inSandboxMode()->getUrl();
        $this->assertTrue($subject->isSandbox());

        $liveUrl = $subject->inLiveMode()->getUrl();
        $this->assertFalse($subject->isSandbox());

        $this->assertNotEquals($sandboxUrl, $liveUrl);
    }

    public function testFakeRequest()
    {
        $text = '{"message":"This should be OK"}';

        $subject = new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $text));

        $response = $subject->payment((object)['empty' => true]);
        $this->assertEquals(json_decode($text, true), $response);
    }
}
