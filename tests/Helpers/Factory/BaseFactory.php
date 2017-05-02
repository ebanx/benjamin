<?php

namespace Tests\Helpers\Factory;

use Faker;

abstract class BaseFactory
{
    protected static function faker()
    {
        return Faker\Factory::create('pt_BR');
    }
}