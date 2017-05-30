<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;

abstract class BaseAdapter
{
    /**
     * @var Config
     */
    protected $config;

    protected function getIntegrationKey()
    {
        return $this->config->isSandbox ? $this->config->sandboxIntegrationKey : $this->config->integrationKey;
    }

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
}
