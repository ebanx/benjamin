<?php
namespace Tests\Helpers\Builders;

use Faker;

class RequestBuilder extends BaseBuilder
{
    /**
     * @var Request
     */
    protected $instance;

    public function __construct(Faker\Generator $faker, Request $instance = null)
    {
        if (!$instance) {
            $instance = $faker->requestModel();
        }

        parent::__construct($faker, $instance);
    }

    /**
     * @return Request
     */
    public function build()
    {
        return $this->instance;
    }
}
