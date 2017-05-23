<?php
namespace Ebanx\Benjamin\Services;

use GuzzleHttp\Client as Guzzle;

class Client
{
    const SANDBOX_URL = 'https://sandbox.ebanx.com/ws/direct';
    const LIVE_URL = 'https://api.ebanx.com/ws/direct';

    const MODE_SANDBOX = 0;
    const MODE_LIVE = 1;

    protected $engine = null;

    private $mode = self::MODE_SANDBOX;

    public function __construct()
    {
        $this->engine = new Guzzle();
    }

    public function post($data)
    {
        return $this->engine->post(
            $this->getUrl(),
            array('json' => $data)
        )->json();
    }

    public function getUrl()
    {
        if ($this->mode === self::MODE_LIVE) {
            return self::LIVE_URL;
        }

        return self::SANDBOX_URL;
    }

    public function inSandboxMode()
    {
        $this->mode = self::MODE_SANDBOX;
        return $this;
    }

    public function inLiveMode()
    {
        $this->mode = self::MODE_LIVE;
        return $this;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function isSandbox()
    {
        return $this->mode === self::MODE_SANDBOX;
    }
}
