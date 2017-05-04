<?php
namespace
{
    use Ebanx\Benjamin\Main;
    use Ebanx\Benjamin\Models\Configs\Config;

    function Benjamin(Config $config)
    {
        return new Main($config);
    }
}
