<?php
namespace Ebanx\Benjamin\Models;

class Payment
{
    public $address; // Class
    public $amountTotal;
    public $currencyCode;
    public $deviceId;
    public $merchantPaymentCode;
    public $note;
    public $notificationUrl = null;
    public $paymentTypeCode;
    public $person; // Class
    public $redirectUrl = null;
    public $items = array(); // array of Item class

    // Sub Account (no idea)
    public $subAccount = null; // Class

    // PJ
    public $responsible; // Person class

    // Boleto, Baloto & EFT
    public $dueDate = null;

    // EFT
    public $eftCode = null;

    // Cards
    public $instalments = null;
    public $card = null; // class
}
