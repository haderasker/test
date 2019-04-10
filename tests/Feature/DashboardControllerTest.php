<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 2/18/18
 * Time: 1:30 PM
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\User;


class DashboardControllerTest extends TestCase
{

    public function testSyncStreamingServers()
    {

        //setup env
        $user = factory(User::class)->create();

        //run my code
        $response = $this->actingAs($user)
            ->get('/admin/server/sync_streaming_servers');

        // Expect the results
        $response->assertRedirect('admin/dashboard');

    }

}
