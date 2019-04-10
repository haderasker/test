<?php

namespace Tests\Unit;

use App\Http\Requests\ApkRequest;
use Tests\TestCase;
use Mockery;
use App\Apis\APK;
use App\Apis\ApiIntegration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApkTest extends TestCase
{
    public function testGetAllApk()
    {
        /**
         * setup env
         */
        // generate dummy data
        $argData = new \stdClass();
        $arg = new \stdClass();
        $arg2 = new \stdClass();
        //object arg
        $arg->id = '4';
        $arg->name = 'apk1';
        $arg->url = 'apk1.com';
        $arg->versionCode = '15';
        $arg->apiId = 'te';
        $arg->apkUpdateDate = '1516218785000';
        //object arg2
        $arg2->id = '4';
        $arg2->name = 'apk2';
        $arg2->url = 'apk2.com';
        $arg2->versionCode = '16';
        $arg2->apiId = 'te';
        $arg2->apkUpdateDate = '1516218785000';

        $argData->object = [
            '0' => $arg,
            '1' => $arg2,
        ];

        $returnedData = new \stdClass();
        $return1 = new \stdClass();
        $return2 = new \stdClass();
        //object return1
        $return1->id = '4';
        $return1->name = 'apk1';
        $return1->url = 'apk1.com';
        $return1->versionCode = '15';
        $return1->apiId = 'te';
        $return1->apkUpdateDate = '17-01-2018 19:53:05';
        //object return2
        $return2->id = '4';
        $return2->name = 'apk2';
        $return2->url = 'apk2.com';
        $return2->versionCode = '16';
        $return2->apiId = 'te';
        $return2->apkUpdateDate = '17-01-2018 19:53:05';

        $returnedData->object = [
            '0' => $return1,
            '1' => $return2,
        ];

        //mocking
        $apkMock = Mockery::Mock(ApiIntegration::class);
        $apkMock->shouldReceive('getAllData')
            ->andReturn($argData)
            ->getMock();
        //run code
        $apkMock = Mockery::mock(APK::class, [$apkMock])->makePartial();
        $apkMock->shouldReceive('convertDate')
            ->andReturn($returnedData->object)
            ->getMock();
        $apkReturn = $apkMock->getAllApk();
        // Expect the results
        $this->assertNotEmpty($apkReturn);
        $this->assertEquals($apkReturn, $returnedData->object);
    }

    public function testConvertDate()
    {
        /**
         * setup env
         */
        // generate dummy data
        $args = new \stdClass();
        $arg = new \stdClass();
        $arg2 = new \stdClass();
        //object arg
        $arg->id = '4';
        $arg->name = 'apk1';
        $arg->url = 'apk1.com';
        $arg->versionCode = '15';
        $arg->apiId = 'te';
        $arg->apkUpdateDate = '1516218785000';
        //object arg2
        $arg2->id = '4';
        $arg2->name = 'apk2';
        $arg2->url = 'apk2.com';
        $arg2->versionCode = '16';
        $arg2->apiId = 'te';
        $arg2->apkUpdateDate = '1516218785000';

        $args->object = [
            '0' => $arg,
            '1' => $arg2,
        ];
        //mocking
        $apkMock = Mockery::Mock(ApiIntegration::class);
        $apkObject = $args->object;
        $dateFormat = date("d-m-Y H:i:s", substr($apkObject[0]->apkUpdateDate, 0, 10));
        //run code
        $apk = new Apk($apkMock);
        $apkConvertedDate = $apk->convertDate($apkObject);
        // Expect the results
        $this->assertNotEmpty($apkConvertedDate);
        $this->assertEquals($apkConvertedDate[0]->apkUpdateDate ?? '', $dateFormat);
    }

    public function testInsertUpdateResult()
    {
        /**
         * setup env
         */
        // generate dummy data
        $argData = [
            "json" => [
                "apkUpdateResult" => [
                    "name" => "test5",
                    "url" => "test5.com",
                    "versionCode" => "2344",
                    "apiId" => "te",
                    "whatsNew" => "werty",
                    "apkUpdateDate" => null,

                ],
                "acceptableVersions" => [],
            ]
        ];
        $fileObject = new \stdClass();
        $fileObject->message = 'wrong';
        $fileObject->ok = false;

        //mocking
        $apiMock = Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('insertData')
            ->andReturn($fileObject)
            ->getMock();
        $requestMock = Mockery::mock(ApkRequest::class);
        $requestMock->shouldReceive('except')->andReturn($argData['json']['apkUpdateResult'])->getMock();
        $requestMock->acceptableVersion = '';

        $apkMock = Mockery::mock(APK::class, [$apiMock])->makePartial();
        $apkMock->shouldReceive('insertFile')
            ->andReturn($fileObject)
            ->getMock();
        //run code
        $apkReturn = $apkMock->insertUpdateResult($requestMock);

        // Expect the results
        $this->assertNotEmpty($apkReturn);
        $this->assertEquals($apkReturn, $fileObject);
    }

    public function testUpdateApkUpdateResult()
    {
        /**
         * setup env
         */
        // generate dummy data
        $argData = [
            "json" => [
                "apkUpdateResult" => [
                    "name" => "test5",
                    "url" => "test5.com",
                    "versionCode" => "2344",
                    "apiId" => "te",
                    "whatsNew" => "werty",
                    "apkUpdateDate" => null,
                ],
                "acceptableVersions" => [],
            ]
        ];
        $fileObject = new \stdClass();
        $fileObject->message = 'Result updated successfully';
        $fileObject->ok = true;

        //mocking
        $apiMock = Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('updateData')
            ->andReturn($fileObject)
            ->getMock();
        $requestMock = Mockery::mock(ApkRequest::class);
        $requestMock->shouldReceive('except')->andReturn($argData['json']['apkUpdateResult'])->getMock();
        $requestMock->acceptableVersion = '';
        $requestMock->id = '';
        //run my code
        $apk = new Apk($apiMock);
        //run code
        $apkReturn = $apk->updateApkUpdateResult($requestMock);

        // Expect the results
        $this->assertNotEmpty($apkReturn);
        $this->assertEquals($apkReturn, $fileObject);
    }

    public function testGetEditResultByApiId()
    {
        /**
         * setup env
         */
        // generate dummy data
        $apiId = 33;
        $argData = new \stdClass();
        $arg = new \stdClass();
        $arg2 = new \stdClass();
        //object arg
        $arg->id = '4';
        $arg->name = 'apk1';
        $arg->url = 'apk1.com';
        $arg->versionCode = '15';
        $arg->apiId = 'te';
        $arg->apkUpdateDate = '1516218785000';
        //object arg2
        $arg2->id = '4';
        $arg2->name = 'apk2';
        $arg2->url = 'apk2.com';
        $arg2->versionCode = '16';
        $arg2->apiId = 'te';
        $arg2->apkUpdateDate = '1516218785000';

        $argData->object = [
            '0' => $arg,
            '1' => $arg2,
        ];
        //mocking
        $apiMock = Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('getDataById')
            ->andReturn($argData)
            ->getMock();

        $versionCodes = $argData->object;
        foreach ($versionCodes as $versionCode)
            $codeVersions[$versionCode->versionCode] = $versionCode->versionCode;
        $returnedFormat = $codeVersions;
        //run my code
        $apk = new Apk($apiMock);
        $returned = $apk->getEditResultByApiId($apiId);
        // Expect the results
        $this->assertNotEmpty($returned);
        $this->assertEquals($returned, $returnedFormat);
    }

    public function testGetUpdateResultByApiId()
    {
        /**
         * setup env
         */
        // generate dummy data
        $apiId = 33;
        $argData = new \stdClass();
        $arg = new \stdClass();
        $arg2 = new \stdClass();
        //object arg
        $arg->id = '4';
        $arg->name = 'apk1';
        $arg->url = 'apk1.com';
        $arg->versionCode = '15';
        $arg->apiId = 'te';
        $arg->apkUpdateDate = '1516218785000';
        //object arg2
        $arg2->id = '4';
        $arg2->name = 'apk2';
        $arg2->url = 'apk2.com';
        $arg2->versionCode = '16';
        $arg2->apiId = 'te';
        $arg2->apkUpdateDate = '1516218785000';

        $argData->object = [
            '0' => $arg,
            '1' => $arg2,
        ];
        //mocking
        $apiMock = Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('getDataById')
            ->andReturn($argData)
            ->getMock();

        $returnedFormat = $argData->object;
        //run my code
        $apk = new Apk($apiMock);
        $returned = $apk->getUpdateResultByApiId($apiId);
        // Expect the results
        $this->assertNotEmpty($returned);
        $this->assertEquals($returned, $returnedFormat);
    }

    public function testGetUpdateResultById()
    {
        /**
         * setup env
         */
        // generate dummy data
        $apiId = 33;
        $argData = new \stdClass();
        $arg = new \stdClass();
        $arg2 = new \stdClass();
        //object arg
        $arg->id = '4';
        $arg->name = 'apk1';
        $arg->url = 'apk1.com';
        $arg->versionCode = '15';
        $arg->apiId = 'te';
        $arg->apkUpdateDate = '1516218785000';
        //object arg2
        $arg2->id = '4';
        $arg2->name = 'apk2';
        $arg2->url = 'apk2.com';
        $arg2->versionCode = '16';
        $arg2->apiId = 'te';
        $arg2->apkUpdateDate = '1516218785000';

        $argData->object = [
            '0' => $arg,
            '1' => $arg2,
        ];
        //mocking
        $apiMock = Mockery::Mock(ApiIntegration::class);
        $apiMock->shouldReceive('getDataById')
            ->andReturn($argData)
            ->getMock();

        $returnedFormat = $argData->object;
        //run my code
        $apk = new Apk($apiMock);
        $returned = $apk->getUpdateResultById($apiId);
        // Expect the results
        $this->assertNotEmpty($returned);
        $this->assertEquals($returned, $returnedFormat);
    }

//    public function testInsertFile()
//    {
//        /**
//         * setup env
//         */
//        // generate dummy data
//        $fakeFile = Mockery::mock(UploadedFile::class)
//            ->shouldReceive('storeAs')
//            ->andReturn('hema')
//            ->getMock();
//
//        $argData = [
//            "name" => "test5",
//            "url" => "test5.com",
//            "versionCode" => "2344",
//            "apiId" => "te",
//            "whatsNew" => "werty",
//            "apkUpdateDate" => null,
//            "apkFile" => $fakeFile,
//        ];
//
//        $fileObject = new \stdClass();
//        $fileObject->message = 'Result inserted successfully';
//        $fileObject->ok = true;
//
//        //mocking
//        $apiMock = Mockery::Mock(ApiIntegration::class);
//        $apiMock->shouldReceive('uploadFile')
//            ->andReturn($fileObject)
//            ->getMock();
//
//        $apkMock = new Apk($apiMock);
//        $returned = $apkMock->insertFile($argData);
//        dd($returned);
//    }

}

