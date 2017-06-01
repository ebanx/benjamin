<?php
namespace Tests\Helpers\Builders;

use Faker;
use Ebanx\Benjamin\Models\BaseModel;

abstract class BaseBuilder
{
    protected $faker;
    protected $instance;

    public function __construct(Faker\Generator $faker, BaseModel $instance)
    {
        $this->faker = $faker;
        $this->instance = $instance;
    }

    public function build()
    {
        return $this->instance;
    }
}
