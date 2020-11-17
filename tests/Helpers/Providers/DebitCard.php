<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\DebitCard as DebitCardModel;

class DebitCard extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\DebitCard
     */
    public function debitCardModel()
    {
        $card = new DebitCardModel();
        $card->autoCapture = true;
        return $card;
    }
}
