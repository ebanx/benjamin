<?php
namespace Tests\Helpers\Providers\es_MX;

use Tests\Helpers\Providers\Person as BasePerson;

class Person extends BasePerson
{
    /**
     * @return \Ebanx\Benjamin\Models\Person
     */
    public function personModel()
    {
        $person = parent::personModel();
        $person->email .= '.mx';
        return $person;
    }
}
