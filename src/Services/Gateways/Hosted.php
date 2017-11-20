<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;

use Ebanx\Benjamin\Models\Request;
use Ebanx\Benjamin\Services\Adapters\RequestAdapter;

class Hosted extends BaseGateway
{
    protected static function getEnabledCountries()
    {
        return Country::all();
    }

    protected static function getEnabledCurrencies()
    {
        return Currency::all();
    }

    protected function getPaymentData(Request $request)
    {
        $adapter = new RequestAdapter($request, $this->config);
        return $adapter->transform();
    }

    public function create(Request $request)
    {
        $this->client->request($this->getPaymentData($payment));
    }
}
