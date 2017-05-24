<?php
namespace Tests\Helpers\Providers\es_MX;

use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = 'Mexico';

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
