<?php
namespace Ebanx\Benjamin\Services\Gateways;

class Webpay extends Flow
{
    protected function getFlowMethod(){
        return 'webpay';
    }
}
