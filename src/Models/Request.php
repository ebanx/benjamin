<?php
namespace Ebanx\Benjamin\Models;

class Request extends BaseModel
{
    /**
     * @var string Country
     */
    public $country;

    /**
     * @var string
     */
    public $email;

    /**
     * @var double
     */
    public $amount;

    /**
     * @var string
     */
    public $merchantPaymentCode;

    /**
     * @var string
     */
    public $orderNumber;

    /**
     * @var array
     */
    public $userValues = array();
}