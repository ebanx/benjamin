<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Http\Client;
use Ebanx\Benjamin\Services\Exchange;

class ExchangeTest extends TestCase
{
    const TEST_RATE = 3.4743;

    private $config;
    private $client;

    public function setup()
    {
        $text = json_encode([
            'currency_rate' => [
                'code' => 'USD',
                'base_code' => '???',
                'name' => 'US Dollar to Something',
                'rate' => self::TEST_RATE,
            ],
            'status' => 'SUCCESS',
        ]);

        $this->config = new Config([
            'baseCurrency' => Currency::USD,
        ]);
        $this->client = new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $text));
    }

    public function testSiteToLocalConvertion()
    {
        $subject = new Exchange($this->config, $this->client);

        $rate = $subject->siteToLocal(Currency::BRL);
        $this->assertEquals(self::TEST_RATE, $rate);
    }

    public function testLocalToSiteConvertion()
    {
        $subject = new Exchange($this->config, $this->client);

        $rate = $subject->localToSite(Currency::BRL);
        $this->assertEquals(self::TEST_RATE, $rate);
    }

    public function testSiteToSiteConvertion()
    {
        $subject = new Exchange($this->config, $this->client);

        $rate = $subject->localToSite(Currency::USD);
        $this->assertEquals(1, $rate);
    }

    public function testGlobalToGlobalConvertion()
    {
        $subject = new Exchange($this->config, $this->client);

        $rate = $subject->localToSite(Currency::EUR);
        $this->assertEquals(0, $rate);
    }

    public function testReturnZeroOnConvertionError()
    {
        $text = json_encode([
            "status" => "ERROR"
        ]);
        $client = new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $text));
        $subject = new Exchange($this->config, $client);

        $rate = $subject->localToSite(Currency::BRL);
        $this->assertEquals(0, $rate);
    }
}
