<?php

namespace Tests\Feature;

use http\Env\Request;
use Tests\TestCase;
use App\User;

class StreamControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testRestartStreams()
    {
        //setup env
        $user = factory(User::class)->create();
        $argData = [
            "0" => "333",
            "1" => "444",
            "2" => "345",
        ];
        $query = http_build_query(array('streamsId' => $argData));
        $url = 'http://streamingservers.devo/admin/streams/restart?' . $query;

        //run my code
        $response = $this->actingAs($user)
            ->get($url);

        // Expect the results
        $response->assertRedirect('admin/streams');
    }

    /**
     * @return void
     */
    public function testChangeServer()
    {
        //setup env
        $user = factory(User::class)->create();
        //run my code
        $response = $this->actingAs($user)
            ->get('admin/stream/change_server/3232');

        // Expect the results
        $response->assertViewHas('stream');
        $response->assertViewHas('streamServers');
        $response->assertViewHas('servers');
    }

    public function testChangeStreamServer()
    {
        //setup env
        $user = factory(User::class)->create();
        $serverIds = [
            "0" => "1",
            "1" => "35",
        ];
        //run my code
        $response = $this->actingAs($user)
            ->post('/admin/stream/change_server', [
                "id" => "3232",
                "serverIds" => $serverIds,
            ]);

        // Expect the results
        $response->assertRedirect('admin/streams');
        $response->assertSessionHas('message');


    }
}
