<?php
namespace Ebanx\Benjamin\Models\Responses;

use Ebanx\Benjamin\Models\BaseModel;

class PaymentTerm extends BaseModel
{
    /**
     * @var integer
     */
    public $instalmentNumber;

    /**
     * @var float
     */
    public $baseAmount;

    /**
     * @var float
     */
    public $localAmountWithTax;

    /**
     * @var float
     */
    public $taxes;

    /**
     * @var boolean
     */
    public $hasInterests;
}
