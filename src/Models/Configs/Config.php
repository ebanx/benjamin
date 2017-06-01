<?php
namespace Ebanx\Benjamin\Models\Configs;

use Ebanx\Benjamin\Models\BaseModel;
use Ebanx\Benjamin\Models\Currency;

class Config extends BaseModel implements AddableConfig
{
    const IOF = 0.0038;

    /**
     * Live integration key.
     *
     * @var string
     */
    public $integrationKey;

    /**
     * Sandbox integration key.
     *
     * @var string
     */
    public $sandboxIntegrationKey;

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
     * The URL to send notifications to.
     *
     * @var string
     */
    public $notificationUrl = null;

    /**
     * The URL to redirect customer after the payment is done.
     *
     * @var string
     */
    public $redirectUrl = null;

    /**
     * Extra information for reports
     *
     * @var array
     */
    public $userValues = array();
}
