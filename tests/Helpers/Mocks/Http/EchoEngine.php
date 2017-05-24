<?php
namespace Tests\Helpers\Mocks\Http;

use GuzzleHttp\Client as Guzzle;

class EchoEngine extends Guzzle
{
    /**
     * @var EchoEngineResponse
     */
    private $responseObj = null;

    /**
     * @param string $response What to respond
     */
    public function __construct($response)
    {
        $this->responseObj = new EchoEngineResponse($response);
    }

    /**
     * @param  string $url     Url to request from
     * @param  array  $options
     * @return EchoEngineResponse
     */
    public function post($url = null, array $options = array())
    {
        return $this->responseObj;
    }
}

class EchoEngineResponse
{
    /**
     * @var string
     */
    private $response = '';

    /**
     * @param string $response What to respond
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function json()
    {
        return json_decode($this->response, true);
    }
}
