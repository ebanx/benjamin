<?php
namespace Tests\Helpers\Mocks\Http;

use Ebanx\Benjamin\Services\Http\Engine;

class EchoEngine extends Engine
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
     * @param  string $url Url to request from
     * @param  array|object $options
     *
     * @return EchoEngineResponse
     * @throws \Exception
     */
    public function post($url = null, $options = [])
    {
        return $this->fakeResponse($url);
    }

    /**
     * @param  string $url Url to request from
     * @param  array $options
     *
     * @return EchoEngineResponse
     * @throws \Exception
     */
    public function get($url = null, $options = [])
    {
        return $this->fakeResponse($url);
    }

    /**
     * @param $url
     *
     * @return mixed|EchoEngineResponse|EchoEngineResponse[]
     * @throws \Exception
     */
    private function fakeResponse($url)
    {
        if (!is_array($this->responses)) {
            return $this->responses;
        }

        $endpoint = str_replace($this->baseUrl, '', $url);
        if ($this->responses[$endpoint]->json()['status'] === 'CONFLICT') {
            throw new \Exception('Conflict: invalid key', 409);
        }
        if ($this->responses[$endpoint]->json()['status'] === 'NOT FOUND') {
            throw new \Exception('Any other http status returned', 404);
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

    public function getContents()
    {
        return $this->response;
    }
}
