<?php
namespace Tests\Helpers\Providers\es_AR;

use Ebanx\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::ARGENTINA;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
