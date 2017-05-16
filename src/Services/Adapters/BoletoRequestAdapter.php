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
        $transformed->document = $this->payment->person->document;

        if ($this->payment->person->type === 'business') {
            $transformed->responsible = $this->getResponsible();
        }

        return $transformed;
    }

    private function getResponsible()
    {
        return (object) array(
            'name' => $this->payment->responsible->name,
            'document' => $this->payment->responsible->document,
            'birth_date' => $this->payment->responsible->birthdate
        );
    }
}
