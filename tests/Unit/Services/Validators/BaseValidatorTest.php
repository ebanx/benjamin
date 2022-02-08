<?php
namespace Tests\Unit\Services\Validators;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Validators\BaseValidator;
use Tests\TestCase;

class BaseValidatorTest extends TestCase
{
    public function testErrorIO()
    {
        $validator = new TestValidator(new Config());
        $this->assertFalse($validator->hasErrors());
        $validator->validate();
        $this->assertTrue($validator->hasErrors());

        foreach ($validator->getErrors() as $error) {
            $this->assertContains($error, [
                'Error 1',
                'Error 2',
                'Error 3',
            ]);
        }
    }
}

class TestValidator extends BaseValidator
{
    public function validate()
    {
        $this->addError('Error 1');
        $this->addAllErrors(['Error 2', 'Error 3']);
    }
}
