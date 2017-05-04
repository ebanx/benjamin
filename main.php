<?php
namespace {
    use Ebanx\Benjamin\Main;
    use Ebanx\Benjamin\Models\Configs\Config;

    function T(Config $config)
    {
        return new Main($config);
    }
}
