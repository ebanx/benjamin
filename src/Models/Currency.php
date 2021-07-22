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
    const UYU = 'UYU';
    const GTQ = 'GTQ';
    const PYG = 'PYG';

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
            self::UYU,
            self::GTQ,
            self::PYG,
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
            Country::GUATEMALA => self::GTQ,
            Country::MEXICO => self::MXN,
            Country::PARAGUAY => self::PYG,
            Country::PERU => self::PEN,
            Country::BOLIVIA => self::BOB,
            Country::URUGUAY => self::UYU,
        ];

        return $relation[$country];
    }

    public static function isGlobal($currency)
    {
        return in_array($currency, self::globalCurrencies());
    }
}
