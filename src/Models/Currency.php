<?php
namespace Ebanx\Benjamin\Models;

class Currency extends BaseModel
{
    const USD = 'USD';
    const EUR = 'EUR';
    const BRL = 'BRL';
    const MXN = 'MXN';
    const ARS = 'ARS';
    const CLP = 'CLP';
    const COP = 'COP';
    const PEN = 'PEN';
    const BOB = 'BOB';

    public static function all()
    {
        return [
            self::USD,
            self::EUR,
            self::BRL,
            self::MXN,
            self::ARS,
            self::CLP,
            self::COP,
            self::PEN,
            self::BOB,
        ];
    }

    public static function globalCurrencies()
    {
        return [
            self::USD,
            self::EUR,
        ];
    }

    public static function localForCountry($country)
    {
        if (!in_array($country, Country::all())) {
            return null;
        }

        $relation = [
            Country::ARGENTINA => self::ARS,
            Country::BRAZIL => self::BRL,
            Country::CHILE => self::CLP,
            Country::COLOMBIA => self::COP,
            Country::ECUADOR => self::USD,
            Country::MEXICO => self::MXN,
            Country::PERU => self::PEN,
            Country::BOLIVIA => self::BOB,
        ];

        return $relation[$country];
    }

    public static function isGlobal($currency)
    {
        return in_array($currency, self::globalCurrencies());
    }
}
