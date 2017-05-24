<?php
namespace Ebanx\Benjamin\Services\Adapters;

class CashRequestAdapter extends BrazilRequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();
        $transformed->bypass_boleto_screen = true;

        return $transformed;
    }

    protected function transformPayment()
    {
        $transformed = parent::transformPayment();

        return $transformed;
    }
}
