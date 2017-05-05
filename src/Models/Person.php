<?php
namespace Ebanx\Benjamin\Models;

class Person extends BaseModel
{
    const TYPE_PERSONAL = "personal";
    const TYPE_BUSINESS = "business";

    /**
     * The type of customer.
     * Supported person types: 'personal' and 'business'.
     *
     * @var string
     */
    public $type = self::TYPE_PERSONAL;

    /**
     * Customers birthdate.
     *
     * @var \DateTime
     */
    public $birthdate;

    /**
     * Customers document.
     *
     * @var string
     */
    public $document;

    /**
     * Customers email.
     *
     * @var string
     */
    public $email;

    /**
     * Customers IP.
     *
     * @var string
     */
    public $ip;

    /**
     * Customers name.
     *
     * @var string
     */
    public $name;

    /**
     * Customers phone number.
     *
     * @var string
     */
    public $phoneNumber;
}
