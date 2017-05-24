<?php
namespace Ebanx\Benjamin\Services\Http;

use GuzzleHttp\Client as Guzzle;

class Client
{
    const SANDBOX_URL = 'https://sandbox.ebanx.com/ws/direct';
    const LIVE_URL = 'https://api.ebanx.com/ws/direct';

    const MODE_SANDBOX = 0;
    const MODE_LIVE = 1;

    /**
     * @var Guzzle
     */
    protected $engine = null;

    /**
     * @var integer
     */
    private $mode = self::MODE_SANDBOX;

    public function __construct()
    {
        $this->engine = new Guzzle();
    }

    /**
     * @param  object|array $data Any data you want to send
     * @return array
     */
    public function post($data)
    {
        return $this->engine->post(
            $this->getUrl(),
            array('json' => $data)
        )->json();
    }

    /**
     * Current endpoint url
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->mode === self::MODE_LIVE) {
            return self::LIVE_URL;
        }

        return self::SANDBOX_URL;
    }

    /**
     * Sets the client to sandbox mode
     *
     * @return Client
     */
    public function inSandboxMode()
    {
        $this->mode = self::MODE_SANDBOX;
        return $this;
    }

    /**
     * Sets the client to live mode
     *
     * @return Client
     */
    public function inLiveMode()
    {
        $this->mode = self::MODE_LIVE;
        return $this;
    }

    /**
     * @return integer
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->mode === self::MODE_SANDBOX;
    }
}
