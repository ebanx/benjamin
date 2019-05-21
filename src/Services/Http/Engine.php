<?php
namespace Ebanx\Benjamin\Services\Http;

use Ebanx\Benjamin\Facade;

class Engine
{
    /**
     * @var String
     */
    private $response = '';

    /**
     * @var array
     */
    private $curlInfo;

    /**
     * @var array;
     */
    private $userAgentInfo = [];
    /**
     * @var string;
     */
    private $formattedUserAgentInfo = '';
    /**
     * @var string;
     */
    private $contentType = 'application/x-www-form-urlencoded';
    /**
     * @param String $method
     * @param String $url
     * @param array|object|boolean $data
     *
     * @return $this
     * @throws \Exception
     */
    private function sendRequest($method, $url, $data = false)
    {
        $curlHandler = curl_init();

        if ($method === 'POST') {
            curl_setopt($curlHandler, CURLOPT_POST, 1);

            if ($data) {
                $this->contentType = 'application/json';
                curl_setopt($curlHandler, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        if ($method === 'GET' && $data) {
            $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $this->buildHeaders());
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);

        $this->response = curl_exec($curlHandler);

        $this->curlInfo = curl_getinfo($curlHandler);
        $httpCode = curl_getinfo($curlHandler)['http_code'];

        if ($httpCode >= 400) {
            throw new \Exception($this->json()['status'], $httpCode);
        }

        curl_close($curlHandler);

        return $this;
    }

    /**
     * @param String $url
     * @param array|object $data
     *
     * @return Engine
     * @throws \Exception
     */
    public function post($url, $data)
    {
        return $this->sendRequest('POST', $url, $data);
    }

    /**
     * @param String $url
     * @param bool|array|object $data
     *
     * @return Engine
     * @throws \Exception
     */
    public function get($url, $data = false)
    {
        return $this->sendRequest('GET', $url, $data);
    }

    /**
     * @return array
     */
    public function json()
    {
        return json_decode($this->response, true);
    }

    /**
     * @return String
     */
    public function getContents()
    {
        return $this->response;
    }

    public function getInfo()
    {
        return $this->curlInfo;
    }

    public function addUserAgentInfo($userValue)
    {
        array_push($this->userAgentInfo, $userValue);
    }

    public function getUserAgentInfo()
    {
        return $this->userAgentInfo;
    }

    public function getFormattedUserAgentInfo()
    {
        return $this->formattedUserAgentInfo;
    }

    private function formatUserAgentInfo()
    {
        if (empty($this->formattedUserAgentInfo)) {
            $formattedUserAgentInfo = ['X-Ebanx-Client-User-Agent: SDK-PHP/' . Facade::VERSION . ' ' . join(' ', $this->userAgentInfo)];
            $this->formattedUserAgentInfo = $formattedUserAgentInfo;
        }
        return $this->formattedUserAgentInfo;
    }

    private function buildHeaders()
    {
        return array_merge(
            $this->formatUserAgentInfo(),
            $this->getContentTypeHeader()
        );
    }

    private function getContentTypeHeader()
    {
        return ['Content-Type: ' . $this->contentType];
    }
}
