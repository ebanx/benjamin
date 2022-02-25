<?php
namespace Ebanx\Benjamin\Services\Adapters;


class DebitCardPaymentAdapter extends BrazilPaymentAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = $this->payment->debit_card->type;
        $transformed->card = $this->transformCard();
        $transformed->manual_review = $this->payment->manualReview;

        return $transformed;
    }

    private function transformCard()
    {
        return (object) [
            'threeds_eci' => $this->payment->debit_card->threedsEci,
            'threeds_cryptogram' => $this->payment->debit_card->threedsCryptogram,
            'threeds_xid' => $this->payment->debit_card->threedsXid,
            'threeds_version' => $this->payment->debit_card->threedsVersion,
            'threeds_trxid' => $this->payment->debit_card->threedsTrxid,
            'token' => $this->payment->debit_card->token
        ];
    }

}