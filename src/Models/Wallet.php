<?php

namespace Ebanx\Benjamin\Models;

class Wallet extends BaseModel
{
    const MERCADOPAGO = 'mercadopago';
    const PAYPAL = 'paypal';
    const PICPAY = 'picpay';
    const MACH_PAY = 'machpay';
    const NEQUI = 'nequi';

    public static function localForCountry($country)
    {
        if (! in_array($country, Country::all())) {
            return null;
        }

        $relation = [
            Country::ARGENTINA => [self::MERCADOPAGO],
            Country::BRAZIL    => [self::MERCADOPAGO, self::PAYPAL, self::PICPAY],
            Country::CHILE     => [self::MACH_PAY],
            Country::COLOMBIA  => [self::NEQUI],
            Country::MEXICO    => [self::MERCADOPAGO]
        ];

        return $relation[$country];
    }
}
