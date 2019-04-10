<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

//        $inserted = [
//            [
//                "message"=> "Result inserted successfully",
//                "ok"=> true,
//                "object"=> null,
//            ]
//        ];
//        $url = '/admin/apk';
//
//        //mocking
//        $apkMock = Mockery::Mock(Apk::class );
//        $apkMock->shouldReceive('redirectWithMessage')
//                ->andReturn($inserted , $url);
//
//        $apkMock->shouldReceive('insertUpdateResult')
//                ->getMock();
//
//        $codeAPISMock = Mockery::Mock(CodeAPIS::class );
//
//        $requestMock = Mockery::Mock(ApkRequest::class );
//
//        //run code
//        $apk = new ApkController($apkMock ,  $codeAPISMock);
//
//        $apkReturn = $apk->insertAPK($requestMock);
//
//        // Expect the results
//        $this->assertNotEmpty($apkReturn);
//        $this->assertEquals($apkReturn[0]['message'] , $inserted[0]['message'] );
}
