<?php

namespace Tests\Helpers\Factory;

use Ebanx\Benjamin\Models\Payment as PaymentModel;

class Payment extends BaseFactory
{
    public static function valid()
    {
        $faker = self::faker();

        $payment = new PaymentModel();
        $payment->address = Address::valid();
        $payment->person = Person::valid();
        $payment->amountTotal = $faker->randomFloat(2, 0, 1000);
        $payment->currencyCode = CurrencyCode::valid();
        $payment->deviceId = $faker->sha256;
        $payment->merchantPaymentCode = $faker->md5;
        $payment->note = 'Fake payment created by PHPUnit.';

        // TODO: Create ItemFactory
        $payment->items = array();

        return $payment;
    }
}
