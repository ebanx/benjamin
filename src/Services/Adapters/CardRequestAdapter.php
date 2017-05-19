<?php
namespace Ebanx\Benjamin\Services\Adapters;

class CardRequestAdapter extends RequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();

        return $transformed;
    }

    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = $this->payment->card->type;
        $transformed->create_token = $this->payment->card->createToken;
        $transformed->token = $this->payment->card->token;
        $transformed->instalments = $this->payment->instalments;
        $transformed->creditcard = $this->transformCard();

        // TODO: Abstract brazilian fields
        $transformed->person_type = $this->payment->person->type;
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
            'birth_date' => $this->payment->responsible->birthdate->format('d/m/Y')
        );
    }

    private function transformCard()
    {
        return (object) array(
            'card_number' => $this->payment->card->number,
            'card_name' => $this->payment->card->name,
            'card_due_date' => $this->payment->card->dueDate->format('m/Y'),
            'card_cvv' => $this->payment->card->cvv,
            'auto_capture' => $this->payment->card->autoCapture,
            'token' => $this->payment->card->token
        );
    }
}
