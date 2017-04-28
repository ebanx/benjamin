<?php
namespace Ebanx\Benjamin\Models;

class Person
{
    const TYPE_PERSONAL = "personal";
    const TYPE_BUSINESS = "business";

    public $personType = self::TYPE_PERSONAL;
    public $birthdate;
    public $document;
    public $email;
    public $ip;
    public $name;
    public $phoneNumber;
}
