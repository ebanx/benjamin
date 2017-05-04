<?php
namespace Tests\Helpers\Providers;

use Faker;

abstract class BaseProvider
{
    protected $faker;

    public function __construct(Faker\Generator $faker)
    {
        $this->faker = $faker;
    }
}
