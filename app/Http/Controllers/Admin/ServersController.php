<?php

namespace App\Http\Controllers\Admin;

use App\Apis\Server;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Schema;


class ServersController extends Controller
{

    private $servers;

    function __construct(Server $server)
    {
        $this->servers = $server;
        $this->middleware('auth');
    }

    /**
     * Display a listing of servers
     * @return \Illuminate\View\View
     * @param Request $request
     *
     */
    public function index(Request $request)
    {
        $servers = $this->servers->serverPaginationPages($request);
        $serversList = $this->servers->serverList($servers);
        $servers->setPath('/admin/servers');

        return view('admin.servers.index', compact('servers', "serversList"));
    }

    /**
     * Show the form for creating a new servers
     *
     * @return \Illuminate\View\View
     */
    public function createServer()
    {
        return view('admin.servers.create');
    }

    /**
     * Store a newly created servers in storage.
     *
     * @param Request $getServer
     * @return \Illuminate\Http\RedirectResponse
     * @internal param CreateServersRequest|Request $request
     */
    public function checkServer(Request $getServer)
    {
        $this->validate($getServer, [
            "name" => "required",
            "ipUrl" => "required|url",
            "base_url" => "required|url"
        ]);

        $servers = $this->servers->serverInfo($getServer->ipUrl);
        if (is_null($servers)) {
            return redirect('/admin/server/create')->withInput()->withMessage("can't add this server");
        }

        $network_names = $this->servers->networkName($servers->networkInfoResults);

        $server["name"] = $getServer->name;
        $server["base_url"] = $getServer->base_url;
        $server["ipUrl"] = $getServer->ipUrl;
        $server['network_names'] = $network_names;

        return view('admin.servers.create', compact("server"));
    }

    public function addServer(Request $getServer)
    {
        $this->validate($getServer, [
            "ethName" => "required",
            "maxBandwidth" => "required"
        ]);
        $server["name"] = $getServer->name;
        $server["baseUrl"] = $getServer->base_url;
        $server["ipUrl"] = $getServer->ipUrl;
        $server["ethName"] = $getServer->ethName;
        $server["maxBandwidth"] = $getServer->maxBandwidth;
        $inserted = $this->servers->insertServer($server);

        return redirect("admin/servers")->withMessage($inserted->message);

    }


    /**
     * Show the form for editing the specified servers.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function editServer($id)
    {

        $server = $this->servers->getServerByID($id);
        $servers = $this->servers->getAllStreamingServer();
        $serversList = $this->servers->serverList($servers);

        $servers = $this->servers->serverInfo($server->ipUrl);
        if($servers != null) {
            $network_names = $this->servers->networkName($servers->networkInfoResults);
            $server->network_names = $network_names;
        }

        return view('admin.servers.edit', compact('server', "serversList"));
    }

    /**
     * Update the specified servers in storage.
     * @param Request $requestedServer
     * @return \Illuminate\Http\RedirectResponse
     * @internal param UpdateServersRequest|Request $request
     *
     * @internal param int $id
     */
    public function updateServer(Request $requestedServer)
    {

        $server["id"] = $requestedServer->server_id;
        $server["name"] = $requestedServer->name;
        $server["baseUrl"] = $requestedServer->base_url;
        $server["ipUrl"] = $requestedServer->ipUrl;
        if($requestedServer->ethName) {
            $server["ethName"] = $requestedServer->ethName;
        }
        $server["maxBandwidth"] = $requestedServer->maxBandwidth;
        $updated = $this->servers->updateServer($server);
        return $this->servers->redirectWithMessage($updated, "admin/servers", "Server Updated Successfully");

    }

    /**
     * Remove the specified servers from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteServerRequest($id)
    {
        $deleted = $this->servers->deleteServerRequest($id);
        $serverId = $id;

        if (isset($deleted->ok) && !empty($deleted->ok)) {
            if ($deleted->ok == 1) {
                return view("admin.servers.deleteRequest", compact("deleted", "serverId"));
            }
        } else {
            return $this->servers->redirectWithMessage($deleted, "admin/servers", "");
        }
    }

    public function deleteServerConfirmed($id)
    {
        $deleted = $this->servers->deleteServerConfirmed($id);

        return $this->servers->redirectWithMessage($deleted, "admin/servers", "");
    }

    public function onlineDevices(Request $request, $serverId, $serverName)
    {
        $allDevices = $this->servers->serverDevicesPaginationPages($request , $serverId );
        $devices = $allDevices['devices'];
        $myCount = $allDevices['count'];
        $devices->setPath('/admin/server/online-devices/' . $serverId . '/' . $serverName);

        return view("admin.servers.online_device", compact("devices", 'serverName' , 'myCount'));
    }

    public function progress(Request $request, $id)
    {
        $server = $this->servers->getServerByID($id);
        $serverId = $server->id;
        $serverName = $server->name;
        $serverUrl = $server->ipUrl . '/streams/getAllStreams';
        $streams = $this->servers->serverStreamPaginationPages($request, $serverUrl);
        $streams->setPath('/admin/server/progress/' . $id);

        return view("admin.servers.progress", compact("streams", "serverId", "serverName"));
    }


}
