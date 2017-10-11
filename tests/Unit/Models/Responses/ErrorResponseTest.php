<?php
namespace Ebanx\Benjamin\Models\Responses;

use Tests\TestCase;

class ErrorResponseTest extends TestCase
{
    public function testingGetters()
    {
        $error = new ErrorResponse([
            'code' => 0,
            'message' => 'test',
        ]);

        $this->assertEquals(0, $error->getCode());
        $this->assertEquals('test', $error->getMessage());
    }
}
