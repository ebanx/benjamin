<?php
namespace Tests\Helpers\Providers\es_PE;

use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = 'Peru';

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
