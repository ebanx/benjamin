<?php
namespace Tests\Helpers\Providers\es_AR;

use Tests\Helpers\Providers\Person as BasePerson;

class Person extends BasePerson
{
    /**
     * @return \Ebanx\Benjamin\Models\Person
     */
    public function personModel()
    {
        $person = parent::personModel();
        $person->email .= '.ar';
        return $person;
    }
}
