<?php
namespace Tests\Unit\Services\Http;

use Ebanx\Benjamin\Facade;
use Ebanx\Benjamin\Services\Http\Engine;
use Tests\TestCase;

class EngineTest extends TestCase
{
    /**
     * @throws \Exception Should not be thrown in this test.
     */
    public function testGetRequestWithoutData()
    {
        $url = 'http://portquiz.net';
        $engine = new Engine();

        $response = $engine->get($url);

        $this->assertContains('You have reached this page on port <b>80</b>', $response->getContents());
        $this->assertEquals('http://portquiz.net/', $engine->getInfo()['url']);
    }

    /**
     * @throws \Exception Should not be thrown in this test.
     */
    public function testGetRequestWithData()
    {
        $url = 'http://portquiz.net';
        $data = ['hash' => 'teste'];
        $engine = new Engine();

        $response = $engine->get($url, $data);

        $this->assertContains('You have reached this page on port <b>80</b>', $response->getContents());
        $this->assertEquals('http://portquiz.net/?hash=teste', $engine->getInfo()['url']);
    }

    /**
     * @throws \Exception Should not be thrown in this test.
     */
    public function testPostRequestWithData()
    {
        $url = 'http://portquiz.net';
        $data = ['hash' => 'teste', 'key' => 'working'];
        $engine = new Engine();

        $response = $engine->post($url, $data);

        $this->assertContains('You have reached this page on port <b>80</b>', $response->getContents());
        $this->assertEquals('http://portquiz.net/', $engine->getInfo()['url']);
    }

    public function testPostRequestWithError()
    {
        $url = 'http://portquiz.net/not-found';
        $data = ['hash' => 'teste'];
        $engine = new Engine();

        try {
            $engine->post($url, $data);
        } catch (\Exception $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testPostRequestWithCustomUserAgentData()
    {
        $url = 'http://portquiz.net';
        $data = ['hash' => 'teste', 'key' => 'working'];
        $engine = new Engine();
        $engine->addUserAgentInfo('test_user_value');

        $response = $engine->post($url, $data);
        $this->assertContains('You have reached this page on port <b>80</b>', $response->getContents());
        $this->assertEquals('http://portquiz.net/', $engine->getInfo()['url']);
        $this->assertEquals('["X-Ebanx-Client-User-Agent: SDK-PHP\/'. Facade::VERSION . ' test_user_value"]', json_encode($response->getFormattedUserAgentInfo()));
    }
    public function testMultiplePostsWithCustomUserAgentData()
    {
        $url = 'http://portquiz.net';
        $data = ['hash' => 'teste', 'key' => 'working'];
        $engine = new Engine();
        $engine->addUserAgentInfo('test_user_value');

        $engine->post($url, $data);
        $engine->post($url, $data);
        $response = $engine->post($url, $data);
        $this->assertContains('You have reached this page on port <b>80</b>', $response->getContents());
        $this->assertEquals('http://portquiz.net/', $engine->getInfo()['url']);
        $this->assertEquals('["X-Ebanx-Client-User-Agent: SDK-PHP\/'. Facade::VERSION . ' test_user_value"]', json_encode($response->getFormattedUserAgentInfo()));
    }
}
