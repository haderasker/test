<?php

namespace App\Http\Controllers\Admin;

use App\Apis\Server;
use App\Config;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $server, $url;

    function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function myData()
    {
        $id = Auth::user()->id;

        $oldConfig = Config::where('user_id', $id)->first();

        if (empty($oldConfig)) {
            $base_url = config("app.api_url")[0];
        } else {
            $base_url = $oldConfig->api_url;
        }

        return $base_url;
    }

    /**
     * Index page
     * @return \Illuminate\View\View
     * @internal param Request $request
     *
     */
    public function index()
    {
        $primaryServerUrl = $this->myData();
        $servers = $this->server->getAllStreamingServer();
        $allConfigurations = $this->server->getAllConfigurations()->object;


        return view('admin.dashboard.index', compact("primaryServerUrl", "servers", "allConfigurations"));
    }


    public function serverInfoResult(Request $request)
    {
        $serverId = $request->id;

        if ($serverId == 0) {
            $serverUrl = $this->myData();

        } else {
            $serverUrl = $request->url;
        }
        $servers = array();
        try {
            if ($serverId == 0) {
                $servers = $this->server->serverInfoPrimary($serverUrl);
            } else {
                $servers = $this->server->serverInfo($serverUrl);
            }

        } catch (\Exception $ex) {

        }

        if ($servers->cpu <= 50)
            $servers->cpuBarColor = "#1aba1c";
        if ($servers->cpu >= 50 && $servers->cpu <= 70)
            $servers->cpuBarColor = "#ffc539";
        if ($servers->cpu > 70)
            $servers->cpuBarColor = "red";
        if ($servers->ram <= 50)
            $servers->ramBarColor = "#1aba1c";
        if ($servers->ram >= 50 && $servers->ram <= 70)
            $servers->ramBarColor = "#ffc539";
        if ($servers->ram > 70)
            $servers->ramBarColor = "red";
        $servers->serverId = $request->id;
//            $servers["streamsCount"] = ChannelServer::where("server_id", $server->id)->where("server_type", "master")->count();
//            $servers["channelStoppedCount"] = ChannelServer::where("server_id", $server->id)->where("server_type", "master")->where("stopped_manually", 1)->count();
//            $servers["onlineChannels"] = isset($getChannels["channelsOnline"]) && !empty($getChannels["channelsOnline"]) ? $getChannels["channelsOnline"] : 0;
//            $servers["offlineChannels"] = isset($getChannels["channelsOffline"]) && !empty($getChannels["channelsOffline"]) ? $getChannels["channelsOffline"] : 0;
        return response()->json($servers);
//            print_r($servers);
//        }

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncStreamingServers(): \Illuminate\Http\RedirectResponse
    {
        $getSyncStreamingServers = $this->server->getSyncStreamingServers();

        return redirect('admin/dashboard')->withMessage($getSyncStreamingServers->message);
    }

    public function changeApiUrl(Request $request)
    {
        $this->validate($request, [
            "url" => 'required',
        ]);

        $id = Auth::user()->id;
        $url = ($request->url);
        $myConfig = [
            'user_id' => $id,
            'api_url' => $url
        ];
        $oldConfig = Config::where('user_id', $id)->first();
        if (empty($oldConfig)) {
            $created = Config::create($myConfig);
        } else {
            $updated = $oldConfig->update($myConfig);
        }

//        $dd = session(['myData'=> $myData ]);

        return redirect('admin/dashboard')->withMessage('Changed Successfully');
    }

    public function changeConfig(Request $request)
    {
        $configed = $this->server->postAllConfigurations($request);

        return redirect('admin/dashboard')->withMessage($configed->message);


    }

}