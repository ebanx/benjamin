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

    public static function all()
    {
        return array(
            self::ARGENTINA,
            self::BRAZIL,
            self::CHILE,
            self::COLOMBIA,
            self::MEXICO,
            self::PERU
        );
    }
}
