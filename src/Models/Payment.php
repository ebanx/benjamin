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
     * The URL to send notifications for this payment.
     *
     * @var string
     */
    public $notificationUrl = null;

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
     * A SubAccount object.
     *
     * @var SubAccount
     */
    public $subAccount = null;

    /**
     * Object containing the company’s responsible person information.
     *
     * @var Person
     */
    public $responsible;

    /**
     * Expiry date of the payment.
     * Only applicable to Boleto, Baloto and EFT.
     *
     * @var \DateTime
     */
    public $dueDate = null;

    /**
     * Code for the customer’s bank.
     * Only applicable to EFT.
     *
     * @var string
     */
    public $eftCode = null;

    /**
     * Number of instalments.
     * Only applicable to Credit and Debit Card.
     *
     * @var int
     */
    public $instalments = null;

    /**
     * A Card object.
     * 
     * @var Card
     */
    public $card = null; // class
}
