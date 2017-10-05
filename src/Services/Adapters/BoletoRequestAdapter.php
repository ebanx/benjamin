<?php
namespace Ebanx\Benjamin\Services\Adapters;

class BoletoRequestAdapter extends BrazilRequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();
        $transformed->bypass_boleto_screen = true;

        return $transformed;
    }
}
