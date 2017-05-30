<?php
namespace Tests\Helpers\Mocks\Http;

use Ebanx\Benjamin\Services\Http\Client;

class ClientForTests extends Client
{
    public function __construct($engine = null)
    {
        parent::__construct();

        if ($engine != null) {
            $this->engine = $engine;
        }
    }

    public function withEngine($engine)
    {
        $this->engine = $engine;
        return $this;
    }
}
