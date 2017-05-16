<?php
namespace Ebanx\Benjamin\Services\Adapters;

class BoletoRequestAdapter extends RequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();
        $transformed->bypass_boleto_screen = true;
        $transformed->person_type = $this->payment->person->type;

        return $transformed;
    }

    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = 'boleto';

        return $transformed;
    }
}
