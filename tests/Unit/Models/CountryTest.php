<?php
namespace Tests\Unit\Models;

use Tests\TestCase;
use Ebanx\Benjamin\Models\Country;

class CountryTest extends TestCase
{
    public function testIsoCodesToCountry()
    {
        $this->assertEquals(Country::ARGENTINA, Country::fromIso('AR'));
        $this->assertEquals(Country::BRAZIL, Country::fromIso('BR'));
        $this->assertEquals(Country::CHILE, Country::fromIso('CL'));
        $this->assertEquals(Country::COLOMBIA, Country::fromIso('CO'));
        $this->assertEquals(Country::ECUADOR, Country::fromIso('EC'));
        $this->assertEquals(Country::MEXICO, Country::fromIso('MX'));
        $this->assertEquals(Country::PERU, Country::fromIso('PE'));
        $this->assertNull(Country::fromIso('ZZ'));
    }

    public function testCountryToIsoCodes()
    {
        $this->assertEquals('AR', Country::toIso(Country::ARGENTINA));
        $this->assertEquals('BR', Country::toIso(Country::BRAZIL));
        $this->assertEquals('CL', Country::toIso(Country::CHILE));
        $this->assertEquals('CO', Country::toIso(Country::COLOMBIA));
        $this->assertEquals('EC', Country::toIso(Country::ECUADOR));
        $this->assertEquals('MX', Country::toIso(Country::MEXICO));
        $this->assertEquals('PE', Country::toIso(Country::PERU));
        $this->assertNull(Country::toIso('Ebanxland'));
    }
}
