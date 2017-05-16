<?php
namespace Tests;

require 'main.php';

use Dotenv\Dotenv;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadEnv()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }
}
