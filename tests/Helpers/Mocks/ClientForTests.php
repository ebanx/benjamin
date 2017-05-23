<?php
namespace Tests\Helpers\Mocks;

use Ebanx\Benjamin\Services\Client;

class ClientForTests extends Client
{
    public function __construct($engine = null)
    {
        parent::__construct();

        if ($engine != null) {
            $this->engine = $engine;
        }
    }
}
