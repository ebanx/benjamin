<?php
namespace Ebanx\Benjamin\Services\Adapters;

class BoletoPaymentAdapter extends BrazilPaymentAdapter
{
    public function transform()
    {
        $transformed = parent::transform();
        $transformed->bypass_boleto_screen = true;

        return $transformed;
    }
}
