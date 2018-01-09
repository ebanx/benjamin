<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;

abstract class BaseAdapter
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Needed for all endpoints
     * @var array
     */
    protected $countryCode = [
        Country::ARGENTINA => 'ar',
        Country::BRAZIL => 'br',
        Country::PERU => 'pe',
        Country::MEXICO => 'mx',
        Country::COLOMBIA => 'co',
        Country::CHILE => 'cl',
        Country::ECUADOR => 'ec',
    ];

    protected function getIntegrationKey()
    {
        return $this->config->isSandbox
            ? $this->config->sandboxIntegrationKey
            : $this->config->integrationKey;
    }

    protected function getNotificationUrl()
    {
        return isset($this->config->notificationUrl)
            ? $this->config->notificationUrl
            : '';
    }

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return object
     */
    abstract public function transform();
}
