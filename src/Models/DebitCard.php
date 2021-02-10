<?php

namespace Ebanx\Benjamin\Models;

class DebitCard extends BaseModel
{

    /**
     * If a previously created token is informed,
     * no credit card information is needed.
     *
     * @var string
     */
    public $token;

    /**
     * Card brand.
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $threedsEci;

    /**
     * @var string
     */
    public $threedsCryptogram;

    /**
     * @var string
     */
    public $threedsXid;

    /**
     * @var string
     */
    public $threedsVersion;

    /**
     * @var string
     */
    public $threedsTrxid;
}