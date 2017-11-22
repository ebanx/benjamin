<?php
namespace Ebanx\Benjamin\Services\Adapters;

abstract class BrazilPaymentAdapter extends PaymentAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->person_type = $this->payment->person->type;
        $transformed->document = $this->payment->person->document;

        if ($this->payment->person->type === 'business') {
            $transformed->responsible = $this->getResponsible();
        }

        return $transformed;
    }

    private function getResponsible()
    {
        $payload = array(
            'name'       => $this->payment->responsible->name,
            'document'   => $this->payment->responsible->document,
        );

        if (isset($this->payment->responsible->birthdate)) {
            $payload['birth_date'] = $this->payment->responsible->birthdate->format('d/m/Y');
        }

        return (object) $payload;
    }
}
