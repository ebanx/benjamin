<?php
namespace Tests\Helpers\Providers\es_CO;

use Ebanx\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::COLOMBIA;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
