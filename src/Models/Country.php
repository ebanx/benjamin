<?php
namespace Ebanx\Benjamin\Models;

class Country extends BaseModel
{
    const BRAZIL = 'Brazil';
    const CHILE = 'Chile';
    const COLOMBIA = 'Colombia';
    const MEXICO = 'Mexico';
    const PERU = 'Peru';

    public static function all()
    {
        return array(
            self::BRAZIL,
            self::CHILE,
            self::COLOMBIA,
            self::MEXICO,
            self::PERU
        );
    }
}
