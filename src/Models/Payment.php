<?php
namespace Ebanx\Benjamin\Models;

class Payment extends BaseModel
{
    /**
     * An Address object.
     *
     * @var Address
     */
    public $address;

    /**
     * The amount in the specified currency.
     *
     * @var float
     */
    public $amountTotal;

    /**
     * Three-letter code of the payment currency.
     * Supported currencies: BRL, EUR, MXN, PEN, USD, CLP, COP
     *
     * @var string
     */
    public $currencyCode;

    /**
     * Unique ID to identify the customer’s device.
     *
     * @var string
     */
    public $deviceId;

    /**
     * The payment hash Merchant Payment Code (merchant unique ID).
     *
     * @var string
     */
    public $merchantPaymentCode;

    /**
     * A note about the payment.
     *
     * @var string
     */
    public $note = null;

    /**
     * A Person object.
     *
     * @var Person
     */
    public $person;

    /**
     * The URL the customer should be redirected to.
     *
     * @var string
     */
    public $redirectUrl = null;

    /**
     * An array of Item obejects.
     *
     * @var Item[]
     */
    public $items = array();

    /**
     * Object containing the company’s responsible person information.
     *
     * @var Person
     */
    public $responsible;

    /**
     * The payment method type
     *
     * @var string
     */
    public $type;

#EFT Boleto Baloto
    /**
     * Expiry date of the payment.
     * Only applicable to Boleto, Baloto and EFT.
     *
     * @var \DateTime
     */
    public $dueDate = null;

#EFT
    /**
     * Code for the customer’s bank.
     * Only applicable to EFT.
     *
     * @var string
     */
    public $eftCode = null;

#CREDIT CARD
    /**
     * Number of instalments.
     * Only applicable to Credit Card.
     *
     * @var int
     */
    public $instalments = null;

#CARD
    /**
     * A Card object.
     *
     * @var Card
     */
    public $card = null;
}
