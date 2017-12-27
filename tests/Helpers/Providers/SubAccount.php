<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\SubAccount as SubAccountModel;

class SubAccount extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\SubAccount
     */
    public function subAccountModel()
    {
        $subAccount = new SubAccountModel();
        $subAccount->name = $this->faker->name;
        $subAccount->imageUrl = $this->faker->url;

        return $subAccount;
    }
}
