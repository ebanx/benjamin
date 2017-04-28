<?php
namespace Ebanx\Benjamin\Models;

class DirectRequest extends BaseModel
{
    const MODE_FULL = "full";
    const OPERATION_REQUEST = "request";

    /**
     * Optional parameter to tell EBANX that it should not display
     * a screen where the user can print the boleto bancário.
     *
     * @var boolean
     */
    public $bypassBoletoScreen;

    /**
     * Your unique and secret integration key.
     *
     * @var string
     */
    public $integrationKey;

    /**
     * Currently only full mode is available.
     *
     * @var string
     */
    public $mode = self::MODE_FULL;

    /**
     * Must be 'request'.
     *
     * @var string
     */
    public $operation = self::OPERATION_REQUEST;

    /**
     * A Payment object.
     *
     * @var Payment
     */
    public $payment;

    /**
     * The code of the payment method.
     *
     * @var string
     */
    public $paymentTypeCode;
}
