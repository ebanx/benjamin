<?php
namespace Tests\Unit\Models;

use Tests\TestCase;
use Ebanx\Benjamin\Models\Country;

class CountryTest extends TestCase
{
    public function testIsoCodes()
    {
        $this->assertEquals(Country::ARGENTINA, Country::fromIso('AR'));
        $this->assertEquals(Country::BRAZIL, Country::fromIso('BR'));
        $this->assertEquals(Country::CHILE, Country::fromIso('CL'));
        $this->assertEquals(Country::COLOMBIA, Country::fromIso('CO'));
        $this->assertEquals(Country::MEXICO, Country::fromIso('MX'));
        $this->assertEquals(Country::PERU, Country::fromIso('PE'));
    }
}
