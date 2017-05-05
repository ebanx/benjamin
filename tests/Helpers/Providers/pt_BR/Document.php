<?php
namespace Tests\Helpers\Providers\pt_BR;

use Tests\Helpers\Providers\BaseProvider;

class Document extends BaseProvider
{
    public function businessDocumentNumber($format = true)
    {
        return $this->faker->cnpj($format);
    }

    public function documentNumber($format = true)
    {
        return $this->faker->cpf($format);
    }
}
