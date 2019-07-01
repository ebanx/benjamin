<?php
namespace Tests\Helpers\Providers\es_BO;

use Tests\Helpers\Providers\Person as BasePerson;

class Person extends BasePerson
{
    /**
     * @return \Ebanx\Benjamin\Models\Person
     */
    public function personModel()
    {
        $person = parent::personModel();
        $person->email .= '.bo';
        return $person;
    }
}
