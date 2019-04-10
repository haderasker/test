<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\User;

class ApkControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testIndex()
    {
        //setup env
        $user = factory(User::class)->create();

        //run my code
        $response = $this->actingAs($user)
            ->get('/admin/apk');

        // Expect the results
        $response->assertViewIs('admin.apk.index');
        $response->assertViewHas('apks');
    }

    /**
     * @return void
     */
    public function testInsertAPKView()
    {
        // setup env
        $user = factory(User::class)->create();

        //run my code
        $response = $this->actingAs($user)
            ->get('admin/apk/create');

        // Expect the results
        $response->assertViewIs('admin.apk.create');
        $response->assertViewHas('apis');
    }

    /**
     * @return void
     */
    public function testInsertAPK()
    {
        // setup env
        $user = factory(User::class)->create();
        Storage::fake('apkFile');

        //run my code
        $response = $this->actingAs($user)
            ->post('/admin/apk/create', [
                //"apkFile" => UploadedFile::fake()->create('document.pdf', 40),
                "apkFile" => UploadedFile::fake()->image('avatar.jpg'),
                "name" => "test6",
                "url" => "test6.com",
                "versionCode" => "33",
                "apiId" => "te",
                "whatsNew" => "2222",
            ]);
        // Expect the results
        $response->assertRedirect('/admin/apk');

    }

    /**
     * @return void
     */
    public function testEditAPKView()
    {
        // setup env
        $user = factory(User::class)->create();

        //run my code
        $response = $this->actingAs($user)
            ->get('admin/apk/edit/60');

        // Expect the results
        $response->assertViewIs('admin.apk.edit');
        $response->assertViewHas('apk');
        $response->assertViewHas('apis');
        $response->assertViewHas('codeVersions');
    }

    /**
     * @return void
     */
    public function testUpdateAPK()
    {
        // setup env
        $user = factory(User::class)->create();
        Storage::fake('apkFile');

        //run my code
        $response = $this->actingAs($user)
            ->put('/admin/apk/update', [
                "id" => "61",
                "name" => "test7",
                "url" => "test7.com",
                "versionCode" => "33",
                "apiId" => "te",
                "whatsNew" => "444",
            ]);

        // Expect the results
        $response->assertRedirect('/admin/apk');

    }

    /**
     * @return void
     */
    public function testGetVersionCodeForApi()
    {
        // setup env
        $user = factory(User::class)->create();

        //run my code
        $response = $this->actingAs($user)
            ->get('admin/apk/versionCodes/', [
                "id" => "61",
                "name" => "test7",
                "url" => "test7.com",
                "versionCode" => "33",
                "apiId" => "vn",
                "whatsNew" => "444",
            ]);

        // Expect the results
        $response->assertStatus(200);

    }

}


