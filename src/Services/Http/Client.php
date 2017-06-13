<?php
namespace Ebanx\Benjamin\Services\Http;

use GuzzleHttp;

class Client
{
    const SANDBOX_URL = 'https://sandbox.ebanx.com/ws/';
    const LIVE_URL = 'https://api.ebanx.com/ws/';

    const MODE_SANDBOX = 0;
    const MODE_LIVE = 1;

    const SUCCESS = 'SUCCESS';
    const ERROR = 'ERROR';

    /**
     * @var GuzzleHttp\Client
     */
    protected $engine = null;

    /**
     * @var integer
     */
    private $mode = self::MODE_SANDBOX;

    public function __construct()
    {
        $this->engine = new GuzzleHttp\Client();
    }

    protected function html($url)
    {
        return $this->engine->get($url)->getBody()->getContents();
    }

    /**
     * @param  object|array $data Any data you want to send
     * @param  string       $endpoint The API endpoint you want to call
     * @return array
     */
    protected function post($data, $endpoint)
    {
        return $this->engine->post(
            $this->getUrl() . $endpoint,
            array('json' => $data)
        )->json();
    }

    /**
     * @param  object|array $data Any data you want to send
     * @param  string       $endpoint The API endpoint you want to call
     * @return array
     */
    protected function query($data, $endpoint)
    {
        return $this->engine->get(
            $this->getUrl() . $endpoint,
            array('query' => $data)
        )->json();
    }

    /**
     * @param  object|array $data Payment data payload
     * @return array
     */
    public function payment($data)
    {
        return $this->post($data, 'direct');
    }

    /**
     * @param  object|array $data Payment data payload
     * @return array
     */
    public function request($data)
    {
        return $this->post($data, 'request');
    }

    /**
     * @param  object|array $data Payment data payload
     * @return array
     */
    public function refund($data)
    {
        return $this->query($data, 'refund');
    }

    /**
     * @param  object|array $data Payment data payload
     * @return array
     */
    public function capture($data)
    {
        return $this->query($data, 'capture');
    }

    /**
     * @param  object|array $data Exchange data payload
     * @return array
     */
    public function exchange($data)
    {
        return $this->query($data, 'exchange');
    }

    public function paymentInfo($data)
    {
        return $this->query($data, 'query');
    }

    public function fetchContent($url)
    {
        return $this->html($url);
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
