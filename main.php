<?php
namespace
{
    use Ebanx\Benjamin\Main;
    use Ebanx\Benjamin\Models\Configs\AddableConfig;

    if (!function_exists('EBANX')) {
        /**
         * @param AddableConfig $config,... Configuration objects
         */
        function EBANX(AddableConfig $config)
        {
            $args = func_get_args();

            $instance = new Main();
            return call_user_func_array(array($instance, 'addConfig'), $args);
        }
    }
}
