<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ebanx\Benjamin\Models\BaseModel;

class SubModel extends BaseModel {
    public $foo;
}

class BaseModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstructor()
    {
        $testData = [
            'foo' => 'bar',
            'baz' => 'qux'
        ];
        $subModel = new SubModel($testData);

        $this->assertObjectHasAttribute('foo', $subModel);
        $this->assertTrue($subModel->foo === $testData['foo']);
        $this->assertObjectNotHasAttribute('baz', $subModel);
    }
}
