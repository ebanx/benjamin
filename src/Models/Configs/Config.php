<?php
namespace Ebanx\Benjamin\Models\Configs;

use Ebanx\Benjamin\Models\BaseModel;
use Ebanx\Benjamin\Models\Currency;

class Config extends BaseModel
{
    const IOF = 0.0038;

    /**
     * Live integration key.
     *
     * @var string
     */
    public $integrationKey;

    /**
     * Public live integration key.
     *
     * @var string
     */
    public $publicIntegrationKey;

    /**
     * Sandbox integration key.
     *
     * @var string
     */
    public $sandboxIntegrationKey;

    /**
     * Public sandbox integration key.
     *
     * @var string
     */
    public $publicSandboxIntegrationKey;

    /**
     * Determines if connection should be made using the sandbox environment settings.
     *
     * @var bool
     */
    public $isSandbox = true;

    /**
     * Sets the site default currency ISO code.
     * (BRL, USD, EUR, COP, MXN, CLP, PEN)
     *
     * @var string
     */
    public $baseCurrency = Currency::USD;

    /**
     * IOF Brazilian tax.
     *
     * @var float
     */
    public $iof = self::IOF;

    /**
     * The URL to send notifications to.
     *
     * @var string
     */
    public $notificationUrl = null;
}
