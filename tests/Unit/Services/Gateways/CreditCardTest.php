<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

class CreditCardTest extends TestGateway
{
    public function testBusinessPersonPayment()
    {
        $client = $this->getMockedClient('{"payment":{"hash":"591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4","pin":"670071563","merchant_payment_code":"248b2672f000e293268be28d6048d600","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-16 19:42:05","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2018-11-22","instalments":"1","payment_type_code":"boleto","boleto_url":"https:\/\/sandbox.ebanx.com\/print\/?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4","boleto_barcode":"34191760071244348372714245740007871600000004881","boleto_barcode_raw":"34198716000000048811760012443483721424574000","pre_approved":false,"capture_available":null,"customer":{"document":"40701766000118","email":"sdasneves@r7.com","name":"SR GUSTAVO FERNANDO VALENCIA","birth_date":"1978-03-28"}},"status":"SUCCESS"}');

        $payment = BuilderFactory::payment()->creditCard()->businessPerson()->build();
        $result = Benjamin($this->config)->gateways()->creditCard()->create($payment, $client);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }
}