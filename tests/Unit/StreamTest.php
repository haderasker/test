<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Apis\Streams;
use App\Apis\ApiIntegration;

class StreamTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRestartStream()
    {
        /**
         * setup env
         */
        // generate dummy data
        $argData = [
            '0' => '3182',
            '1' => '3183',
            '2' => '3184',
        ];
        $fileObject = new \stdClass();
        $fileObject->message = 'Streams restarted';
        $fileObject->ok = true;

        //mocking
        $apiMock = \Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('insertData')
            ->andReturn($fileObject)
            ->getMock();

        //run my code
        $streams = new Streams($apiMock);
        $returned = $streams->restartStream($argData);

        // Expect the results
        $this->assertNotEmpty($returned);
        $this->assertEquals($returned, $fileObject);
    }

    public function testChangeServer()
    {
        /**
         * setup env
         */
        // generate dummy data
        $argData = [
            '0' => '1',
            '1' => '35',
        ];
        $stream_id = '3232';
        $fileObject = new \stdClass();
        $fileObject->message = 'stream(s) moved to new servers successfully';
        $fileObject->ok = true;

        //mocking
        $apiMock = \Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('updateData')
            ->andReturn($fileObject)
            ->getMock();

        //run my code
        $streams = new Streams($apiMock);
        $returned = $streams->changeServer($stream_id , $argData);

        // Expect the results
        $this->assertNotEmpty($returned);
        $this->assertEquals($returned, $fileObject);

    }
}
