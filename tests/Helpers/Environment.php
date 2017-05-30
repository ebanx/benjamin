<?php
namespace Tests\Helpers;

use Dotenv\Dotenv;

class Environment
{
    const ENV_VAR_NOT_SET = '__ENV_VAR_NOT_SET_CONST_VALUE__';

    public function __construct()
    {
        (new Dotenv(__DIR__.'/../../'))->load();
    }

    /**
     * @param  string $key     Env var name to read
     * @param  mixed  $default Optional default value
     * @return string
     */
    public function read($key, $default = self::ENV_VAR_NOT_SET)
    {
        $result = getenv($key) ?: $default;
        if ($result === self::ENV_VAR_NOT_SET) {
            throw new \InvalidArgumentException('No such env var '.$key.' found!');
        }

        return $result;
    }
}
