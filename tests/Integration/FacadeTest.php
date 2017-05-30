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
     * @param $ebanx
     */
    public function testGatewayAccessors($ebanx)
    {
        $gateways = $this->getExpectedGateways();

        foreach ($gateways as $gateway) {
            $this->assertTrue(
                method_exists($ebanx, $gateway),
                "Facade has no accessor for gateway \"$gateway\"."
            );

            $this->assertNotNull(
                $this->tryBuildGatewayUsingFacadeAccessor($ebanx, $gateway),
                "Accessor failed to build instance of gateway \"$gateway\"."
            );
        }
    }

    private function tryBuildGatewayUsingFacadeAccessor($facade, $accessor)
    {
        return call_user_func(array($facade, $accessor));
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
