<?php
namespace Tests\Helpers\Providers\es_UY;

use Ebanx\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::URUGUAY;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
