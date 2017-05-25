<?php
namespace Tests\Integration;

use Tests\TestCase;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;

class FacadeTest extends TestCase
{
    public function testMainObject()
    {
        $ebanx = EBANX(new Config(), new CreditCardConfig());
        $this->assertNotNull($ebanx);

        return $ebanx;
    }

    /**
     * @depends testMainObject
     */
    public function testGatewayAccessors($ebanx)
    {
        foreach ($this->getExpectedGateways() as $gateway) {
            $this->assertTrue(
                method_exists($ebanx, $gateway),
                "Facade has no accessor for gateway \"$gateway\"."
            );
        }
    }

    private function getExpectedGateways()
    {
        $result = array();
        $ignore = array(
            '.',
            '..',
            'BaseGateway.php'
        );

        $dir = opendir('src/Services/Gateways');
        while (($file = readdir($dir)) !== false) {
            if (in_array($file, $ignore)) {
                continue;
            }

            $result[] = lcfirst(basename($file, '.php'));
        }
        closedir($dir);

        return $result;
    }
}
