<?php
namespace Tests\Unit\Services\Http;

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
}
