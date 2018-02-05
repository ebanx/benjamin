<?php
namespace Ebanx\Benjamin\Services\Adapters;

class BoletoPaymentAdapter extends BrazilPaymentAdapter
{
    public function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->bypass_boleto_screen = true;

        if (isset($this->payment->dueDate)) {
            $transformed->due_date = $this->payment->dueDate->format('d/m/Y');
        }

        return $transformed;
    }
}
