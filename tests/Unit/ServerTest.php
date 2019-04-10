<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 2/18/18
 * Time: 1:29 PM
 */

namespace Tests\Unit;

use Tests\TestCase;
use App\Apis\Server;
use App\Apis\ApiIntegration;
use App\Apis\Devices;
use Mockery;

class ServerTest extends TestCase
{
    public function testGetSyncStreamingServers()
    {
        /**
         * setup env
         */
        //generate dummy data
        $fileObject = new \stdClass();
        $fileObject->message = 'Streamers synced successfully';
        $fileObject->ok = true;
        //mocking
        $apiMock = Mockery::mock(ApiIntegration::class);
        $apiMock->shouldReceive('getAllData')
                ->andReturn($fileObject)
                ->getMock();
        $deviceMock = Mockery::mock(Devices::class);

        //run my code
        $server = new Server($apiMock,$deviceMock,$apiMock);
        $returned = $server->getSyncStreamingServers();

        // Expect the results
        $this->assertEquals($returned , $fileObject);
    }

}
