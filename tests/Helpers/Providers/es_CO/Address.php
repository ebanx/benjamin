<?php
namespace Tests\Helpers\Providers\es_CO;

use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = 'Colombia';

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
