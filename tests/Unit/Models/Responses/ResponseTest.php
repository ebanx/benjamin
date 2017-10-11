<?php
namespace Ebanx\Benjamin\Models\Responses;

use Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testingGetters()
    {
        $response = new Response([
            'errors' => [
                new ErrorResponse(),
                new ErrorResponse(),
            ],
            'body' => 'test',
        ]);

        $this->assertCount(2, $response->getErrors());
        $this->assertEquals('test', $response->getBody());
        $this->assertTrue($response->hasErrors());
    }
}
