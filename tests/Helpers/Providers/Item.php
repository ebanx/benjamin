<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\Item as ItemModel;

class Item extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\Item
     */
    public function itemModel()
    {
        $item = new ItemModel();
        $item->name = $this->faker->words(2, true);
        $item->description = $this->faker->text(140);
        $item->type = $this->faker->word;
        $item->sku = strtoupper($this->faker->bothify('?-???-######'));

        $item->unitPrice = $this->faker->randomFloat(2, 1, 10);
        $item->quantity = $this->faker->numberBetween(1, 3);

        return $item;
    }

    /**
     * @param integer $count
     * @return ItemModel[]
     */
    public function itemModels($count)
    {
        $result = [];

        for ($i = 0; $i < $count; $i++) {
            $result[] = $this->itemModel();
        }

        return $result;
    }
}
