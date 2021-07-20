<?php
namespace Ebanx\Benjamin\Models;

class Country extends BaseModel
{
    const ARGENTINA = 'Argentina';
    const BRAZIL = 'Brazil';
    const CHILE = 'Chile';
    const COLOMBIA = 'Colombia';
    const ECUADOR = 'Ecuador';
    const GUATEMALA = 'Guatemala';
    const MEXICO = 'Mexico';
    const PARAGUAY = 'Paraguay';
    const PERU = 'Peru';
    const BOLIVIA = 'Bolivia';
    const URUGUAY = 'Uruguay';

    /**
     * @return array
     */
    public static function all()
    {
        return [
            self::ARGENTINA,
            self::BRAZIL,
            self::CHILE,
            self::COLOMBIA,
            self::ECUADOR,
            self::GUATEMALA,
            self::MEXICO,
            self::PARAGUAY,
            self::PERU,
            self::BOLIVIA,
            self::URUGUAY,
        ];
    }

    /**
     * Two letter ISO-3166
     */
    public static function fromIso($code)
    {
        $countryCode = strtoupper($code);
        $table = self::isoCodes();

        if (!array_key_exists($countryCode, $table)) {
            return null;
        }

        return $table[$countryCode];
    }

    /**
     * Two letter ISO-3166
     */
    public static function toIso($country)
    {
        $table = array_flip(self::isoCodes());

        if (!array_key_exists($country, $table)) {
            return null;
        }

        return $table[$country];
    }

    /**
     * @return array
     */
    private static function isoCodes()
    {
        return [
            'AR' => self::ARGENTINA,
            'BR' => self::BRAZIL,
            'CL' => self::CHILE,
            'CO' => self::COLOMBIA,
            'EC' => self::ECUADOR,
            'GT' => self::GUATEMALA,
            'MX' => self::MEXICO,
            'PY' => self::PARAGUAY,
            'PE' => self::PERU,
            'BO' => self::BOLIVIA,
            'UY' => self::URUGUAY,
        ];
    }
}
