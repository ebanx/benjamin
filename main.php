<?php
namespace
{
    use Ebanx\Benjamin\Main;
    use Ebanx\Benjamin\Models\Configs\Config;

    if (!function_exists('EBANX')) {
        function EBANX(Config $config)
        {
            return new Main($config);
        }
    }
}
