<?php
namespace Tests\Unit\Models;

use Tests\TestCase;
use Ebanx\Benjamin\Models\BaseModel;

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
        $subModel = new BaseModelImplementation($testData);

        $this->assertObjectHasAttribute('foo', $subModel);
        $this->assertEquals('bar', $subModel->foo);
        $this->assertObjectNotHasAttribute('baz', $subModel);
    }
}

class BaseModelImplementation extends BaseModel
{
    public $foo;
}
