<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\Card as CardModel;

class Card extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\Card
     */
    public function cardModel()
    {
        $card = new CardModel();
        $card->createToken = false;
        $card->name = $this->faker->name();
        $card->dueDate = $this->faker->creditCardExpirationDate();
        $card->autoCapture = true;
        $card->cvv = '123';
        $card->number = $this->faker->creditCardNumber();
        $card->type = $this->faker->creditCardType();

        return $card;
    }

    public function cardWithOnlyTokenModel()
    {
        $card = new CardModel();
        $card->autoCapture = true;
        $card->token = 'th1s1sat0k3n';

        return $card;
    }
}
