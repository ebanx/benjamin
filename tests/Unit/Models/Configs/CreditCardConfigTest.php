<?php
namespace Tests\Unit\Models\Configs;

use Tests\TestCase;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;

class CreditCardConfigTest extends TestCase
{
    public function testBuildInsterestRates()
    {
        $ccConfig = new CreditCardConfig();
        $ccConfig
            ->addInterest(1, 0.02)
            ->addInterest(2, 0.06)
            ->addInterest(3, 0.10)
            ->addInterest(4, 0.15);

        $this->assertEquals(4, count($ccConfig->interestRates));
    }
}
