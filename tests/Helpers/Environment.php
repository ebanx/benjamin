<?php
namespace Tests\Helpers;

use Dotenv\Dotenv;

class Environment
{
    const ENV_VAR_NOT_SET = '__ENV_VAR_NOT_SET_CONST_VALUE__';

    public function __construct()
    {
        $this->reloadEnv();
    }

    public function reloadEnv()
    {
        (new Dotenv(__DIR__.'/../../'))->load();
    }

    public function read($key, $default = self::ENV_VAR_NOT_SET)
    {
        $result = getenv($key) ?: $default;
        if ($result === self::ENV_VAR_NOT_SET) {
            throw new ArgumentException('No such env var '.$key.' found!');
        }

        return $result;
    }
}
