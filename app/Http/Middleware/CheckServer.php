<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Auth;
use App\Config;
use Illuminate\Support\Facades\Cache;

class CheckServer
{

    public function handle($request, Closure $next)
    {
        $client = new GuzzleHttp\Client();
        try {
            $id = Auth::user()->id;
            $oldConfig = Config::where('user_id' , $id)->first();

            if (empty( $oldConfig)) {
                $base_url = config("app.api_url")[0];
            }
            else {
                $base_url = $oldConfig->api_url ;
            }

            $url = $base_url . '/middleware/health';
            $client->request('GET', $url);

            return $next($request);

        } catch (GuzzleHttp\Exception\RequestException $e) {
            $redis = Redis::connection();
            $redis->del('new_url');
            if ($e) {
                return redirect('/home');

            }
        }
    }
}
