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

    public static function globalCurrencies()
    {
        return array(
            self::USD,
            self::EUR
        );
    }

    public static function localForCountry($country)
    {
        if (!in_array($country, Country::all())) {
            return null;
        }

        $relation = array(
            Country::BRAZIL => self::BRL,
            Country::MEXICO => self::MXN,
            Country::CHILE => self::CLP,
            Country::COLOMBIA => self::COP,
            Country::PERU => self::PEN
        );

        return $relation[$country];
    }

    public static function isGlobal($currency)
    {
        return in_array($currency, self::globalCurrencies());
    }
}
