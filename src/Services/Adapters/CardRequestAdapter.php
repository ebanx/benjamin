<?php
namespace Ebanx\Benjamin\Services\Adapters;

class CardRequestAdapter extends BrazilRequestAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = $this->payment->card->type;
        $transformed->create_token = $this->payment->card->createToken;
        $transformed->token = $this->payment->card->token;
        $transformed->instalments = $this->payment->instalments;
        $transformed->creditcard = $this->transformCard();

        return $transformed;
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
