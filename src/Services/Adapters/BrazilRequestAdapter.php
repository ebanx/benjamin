<?php
namespace Ebanx\Benjamin\Services\Adapters;

abstract class BrazilRequestAdapter extends RequestAdapter
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
        $birthdate = '';
        if (isset($this->payment->responsible->birthdate)) {
            $birthdate = $this->payment->responsible->birthdate->format('d/m/Y');
        }
        return (object) array(
            'name' => $this->payment->responsible->name,
            'document' => $this->payment->responsible->document,
            'birth_date' => $birthdate,
        );
    }
}
