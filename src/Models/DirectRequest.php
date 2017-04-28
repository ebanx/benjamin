<?php
namespace Ebanx\Benjamin\Models;

class DirectRequest extends BaseModel
{
    const MODE_FULL = "full";
    const OPERATION_REQUEST = "request";

    public $bypassBoletoScreen;
    public $integrationKey;
    public $mode = self::MODE_FULL;
    public $operation = self::OPERATION_REQUEST;
    public $payment; // Class
    public $paymentTypeCode; // Gateway dependent
}
