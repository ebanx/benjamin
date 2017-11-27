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
    ];

    protected function getIntegrationKey()
    {
        return $this->config->isSandbox
            ? $this->config->sandboxIntegrationKey
            : $this->config->integrationKey;
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
