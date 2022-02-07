<?php

namespace Tests\Unit\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Wallet;
use Tests\Helpers\Builders\BuilderFactory;

class WalletTestCase extends GatewayTestCase
{
    public function getDigitalWalletSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"5ec27f3b86fa8e3123452345626aec3989aa2ceccdb7","pin":"237274394","country":"co","merchant_payment_code":"xyz-123-1001","order_number":null,"status":"PE","status_date":null,"open_date":"2020-05-18 12:27:38","confirm_date":null,"transfer_date":null,"amount_br":"1.00","amount_ext":"1.00","amount_iof":"0.00","currency_rate":"1.0000","currency_ext":"COP","due_date":"2020-05-21","instalments":"1","payment_type_code":"nequi","redirect_url":"https://api.ebanx.com/ws/redirect/execute?hash=5ec27f3b86fa8e318ddcc9727453626aec3989aa2ceccdb7","pre_approved":false,"capture_available":null},"status":"SUCCESS","redirect_url":"https://api.ebanx.com/ws/redirect/execute?hash=5ec27f3b86fa8e318dd345234523553626aec3989aa2ceccdb7"}';
    }
}
