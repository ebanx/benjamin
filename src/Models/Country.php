<?php
namespace Ebanx\Benjamin\Models;

class Country extends BaseModel
{
    const ARGENTINA = 'Argentina';
    const BRAZIL = 'Brazil';
    const CHILE = 'Chile';
    const COLOMBIA = 'Colombia';
    const MEXICO = 'Mexico';
    const PERU = 'Peru';

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
            self::MEXICO,
            self::PERU,
        ];
    }

    /**
     * Two letter ISO-3166
     * @see
     */
    public static function fromIso($code)
    {
        return [
            'AR' => self::ARGENTINA,
            'BR' => self::BRAZIL,
            'CL' => self::CHILE,
            'CO' => self::COLOMBIA,
            'MX' => self::MEXICO,
            'PE' => self::PERU
        ][strtoupper($code)];
    }
}
