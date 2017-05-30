<?php
namespace Tests\Helpers\Mocks\Http;

use GuzzleHttp\Client as Guzzle;

class EchoEngine extends Guzzle
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var EchoEngineResponse[]|EchoEngineResponse
     */
    private $responses = array();

    /**
     * @param string $response What to respond
     */
    public function __construct($baseUrl, $responses)
    {
        $this->baseUrl = $baseUrl;

        if (!is_array($responses)) {
            $this->responses = new EchoEngineResponse($responses);
            return;
        }

        foreach ($responses as $key => $value) {
            $this->responses[$key] = new EchoEngineResponse($value);
        }
    }

    /**
     * @param  string $url     Url to request from
     * @param  array  $options
     * @return EchoEngineResponse
     */
    public function post($url = null, array $options = array())
    {
        return $this->fakeResponse($url);
    }

    /**
     * @param  string $url     Url to request from
     * @param  array  $options
     * @return EchoEngineResponse
     */
    public function get($url = null, $options = array())
    {
        return $this->fakeResponse($url);
    }

    private function fakeResponse($url)
    {
        if (!is_array($this->responses)) {
            return $this->responses;
        }

        $endpoint = str_replace($this->baseUrl, '', $url);
        return $this->responses[$endpoint];
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
