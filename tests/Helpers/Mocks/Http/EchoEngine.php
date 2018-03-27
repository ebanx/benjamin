<?php
namespace Tests\Helpers\Mocks\Http;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response;
use Tests\Helpers\Providers\Request;

class EchoEngine extends Guzzle
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var EchoEngineResponse[]|EchoEngineResponse
     */
    private $responses = [];

    /**
     * @param string $baseUrl
     * @param string $responses What to respond
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
    public function post($url = null, array $options = [])
    {
        return $this->fakeResponse($url);
    }

    /**
     * @param  string $url     Url to request from
     * @param  array  $options
     * @return EchoEngineResponse
     */
    public function get($url = null, $options = [])
    {
        return $this->fakeResponse($url);
    }

    private function fakeResponse($url)
    {
        if (!is_array($this->responses)) {
            return $this->responses;
        }

        $endpoint = str_replace($this->baseUrl, '', $url);
        if ($this->responses[$endpoint]->json()['status'] === 'CONFLICT') {
            throw new ClientException('Conflict: invalid key', new \GuzzleHttp\Message\Request('GET', $endpoint), new Response(409));
        }
        if ($this->responses[$endpoint]->json()['status'] === 'NOT FOUND') {
            throw new ClientException('Any other http status returned', new \GuzzleHttp\Message\Request('GET', $endpoint), new Response(404));
        }
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

    public function getBody()
    {
        return new EchoEngineResponseBody($this->response);
    }
}

class EchoEngineResponseBody
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }
    public function getContents()
    {
        return $this->response;
    }
}
