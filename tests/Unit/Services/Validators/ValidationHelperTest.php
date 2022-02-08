<?php
namespace Tests\Unit\Services\Validators;

use Tests\TestCase;
use Ebanx\Benjamin\Services\Validators\ValidationHelper;

class ValidationHelperTest extends TestCase
{
    public function testRuleQueue()
    {
        $subject = new ValidationHelper();

        $errors = [];

        $errors = array_merge(
            $errors,
            $subject->min(10)
                ->max(11)
                ->test('Low value', 9)
        );

        $errors = array_merge(
            $errors,
            $subject->min(10)
                ->max(11)
                ->test('High value', 12)
        );

        $this->assertStringContainsString('Low value', $errors[0]);
        $this->assertStringContainsString('10', $errors[0]);

        $this->assertStringContainsString('High value', $errors[1]);
        $this->assertStringContainsString('11', $errors[1]);
    }
}
