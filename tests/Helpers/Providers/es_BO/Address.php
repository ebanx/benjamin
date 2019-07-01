<?php
namespace Tests\Helpers\Providers\es_BO;

use Ebanx\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::BOLIVIA;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
