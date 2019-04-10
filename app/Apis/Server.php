<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 10/12/17
 * Time: 14:00
 */

namespace App\Apis;

use GuzzleHttp;
use Illuminate\Support\Facades\Redis;

class Server
{
    private $url;
    private $baseUrl = "/servers";
    private $baseUrlInfo = "/info";
    private $configUrl = "/config";
    private $server;
    private $devices;
    private $info;
    private $config;

    function __construct(ApiIntegration $apiClass, Devices $devices, ApiIntegration $info, ApiIntegration $config)
    {
//        $this->url = env("API_URL");
        $apiClass->baseUrl = $this->baseUrl;
        $this->server = $apiClass;
        $info->baseUrl = $this->baseUrlInfo;
        $this->info = $info;
        $this->devices = $devices;
        $config->baseUrl = $this->configUrl;
        $this->config = $config;

    }

    public function checkRequest($request)
    {
        $myRequest = $request->fullUrl();

        if (!(str_contains($myRequest, 'page')) && !(str_contains($myRequest, 'num'))) {
            $data = $this->getAllStreamingServer();
            $redis = Redis::connection();
            $redis->set('servers', json_encode($data));
            return $data;
        } else {
            return $this->connectRedis();
        }
    }

    /**
     * @return mixed
     */
    public function connectRedis()
    {
        //redis
        $redis = Redis::connection();
        if (!($redis->exists('servers'))) {
            $data = $this->getAllStreamingServer();
            $redis->set('servers', json_encode($data));
        }
        $response = $redis->get('servers');
        $response = json_decode($response);
        Redis::expire('servers', config("app.expire_redis"));

        return $response;
    }

    /**
     * @param $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function serverPaginationPages($request)
    {
        $response = $this->checkRequest($request);
        //paginate
        $servers = $this->server->paginationPages($response, $request);

        return $servers;
    }


    public function checkStreamRequest($request, $serverUrl, $online, $offline)
    {
        $myRequest = $request->fullUrl();

        if (!(str_contains($myRequest, 'page')) && !(str_contains($myRequest, 'num'))) {
            $getStreams = $this->progress($serverUrl);
            $dataChecked = $getStreams->object;
            $data = $this->checkFilterStatus($dataChecked, $online, $offline);
            $redis = Redis::connection();
            $redis->set('serversStreams-' . $serverUrl, json_encode($data));
            return $data;
        } else {
            return $this->connectStreamRedis($serverUrl);
        }
    }

    public function checkFilterStatus($allData, $online, $offline)
    {
        $myFilterOnline = [];
        $myFilterOffline = [];
        if ($online != null && $offline == null) {
            foreach ($allData as $data) {
                if ($data->progressResult != null && $data->progressResult->online == true) {
                    $myFilterOnline[] = $data;
                }
            }
            return $myFilterOnline;
        } elseif ($offline != null && $online == null) {
            foreach ($allData as $data) {
                if ($data->progressResult == null || $data->progressResult->online == false) {
                    $myFilterOffline[] = $data;
                }
            }
            return $myFilterOffline;
        } elseif ($online != null && $offline != null) {
            return $allData;
        } else {
            return $allData;
        }
    }

    /**
     * @param $serverUrl
     * @return mixed
     */
    public function connectStreamRedis($serverUrl)
    {
        //redis
        $redis = Redis::connection();
        if (!($redis->exists('serversStreams-' . $serverUrl))) {
            $getStreams = $this->progress($serverUrl);
            $data = $getStreams->object;
            $redis->set('serversStreams-' . $serverUrl, json_encode($data));
        }
        $response = $redis->get('serversStreams-' . $serverUrl);
        $response = json_decode($response);
        Redis::expire('serversStreams-' . $serverUrl, config("app.expire_redis"));

        return $response;
    }

    /**
     * @param $request
     * @param $serverUrl
     * @param $online
     * @param  $offline
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function serverStreamPaginationPages($request, $serverUrl, $online = null, $offline = null)
    {
        $response = $this->checkStreamRequest($request, $serverUrl, $online, $offline);
        //paginate
        $servers = $this->server->paginationPages($response, $request);

        return $servers;
    }

    public function checkDeviceRequest($request, $serverId)
    {
        $myRequest = $request->fullUrl();

        if (!(str_contains($myRequest, 'page')) && !(str_contains($myRequest, 'num'))) {
            $data = $this->getOnlineDevices($serverId);
            $redis = Redis::connection();
            $redis->set('serversDevices-' . $serverId, json_encode($data));
            return $data;
        } else {
            return $this->connectDeviceRedis($serverId);
        }
    }

    /**
     * @param $serverId
     * @return mixed
     */
    public function connectDeviceRedis($serverId)
    {
        //redis
        $redis = Redis::connection();
        if (!($redis->exists('serversDevices-' . $serverId))) {
            $data = $this->getOnlineDevices($serverId);
            $redis->set('serversDevices-' . $serverId, json_encode($data));
        }
        $response = $redis->get('serversDevices-' . $serverId);
        $response = json_decode($response);
        Redis::expire('serversDevices-' . $serverId, config("app.expire_redis"));

        return $response;
    }

    /**
     * @param $request
     * @param $serverId
     * @return array
     */
    public function serverDevicesPaginationPages($request, $serverId)
    {
        $response = $this->checkDeviceRequest($request, $serverId);
        $count = count($response);

        //paginate
        $servers = $this->server->paginationPages($response, $request);

        return [
            'devices' => $servers,
            'count' => $count
        ];
    }

    /**
     * @return string
     */
    public function getAllStreamingServer()
    {
        $getServers = $this->server->getAllData("getAllStreamingServer");
        return $getServers->object;

    }

    public function insertServer($serverData)
    {
        $requestServer = $this->server->insertData("insertServer", [
            GuzzleHttp\RequestOptions::JSON => $serverData]);
        return $requestServer;

    }

    public function getServerByID($serverId)
    {
        $getServers = $this->server->getDataById("getStreamingServerById", "?serverId=$serverId");
        return $getServers->object;

    }

    public function deleteServerRequest($serverId)
    {
        $deleted = $this->server->deleteData("deleteServerRequest", "?serverId=$serverId");
        return $deleted;

    }

    public function deleteServerConfirmed($serverId)
    {
        $deleted = $this->server->deleteData("deleteConfirmed", "?serverId=$serverId");
        return $deleted;

    }

    public function updateServer($serverData)
    {
        $updated = $this->server->updateData("updateServer", [GuzzleHttp\RequestOptions::JSON => $serverData]);
        return $updated;

    }

    public function serverList($getServers)
    {
        $servers = array();
        foreach ($getServers as $server) {
            $servers[$server->id] = $server->name;
        }
        return $servers;
    }

    public function serverIds($getServers)
    {
        $servers = array();
        foreach ($getServers as $server) {
            $servers[] = $server->id;
        }
        return $servers;
    }


    public function serverName($servers, $id)
    {
        $serverName = '';
        foreach ($servers as $server) {
            if ($server->id == $id) {
                $serverName = $server->name;
            }
        }
        return $serverName;
    }

    public function serverInfo($serverUrl)
    {

        try {

            $serverInfo = $this->info->requestedDataWithoutApiUrl("$serverUrl/$this->baseUrlInfo/s_server_info");

            return $serverInfo;
        } catch (GuzzleHttp\Exception\RequestException $e) {

            if ($e) {
                return '';
            }
        }

    }


    public function serverInfoPrimary($serverUrl)
    {
        try {
            $serverInfo = $this->info->requestedDataWithoutApiUrl("$serverUrl/$this->baseUrlInfo/m_server_info");
            return $serverInfo;
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e) {
                return '';
            }
        }

    }

    public function networkName($networks)
    {
        $networkNames = [];
        foreach ($networks as $network) {
            $networkNames[$network->networkName] = $network->networkName;
        }
        return $networkNames;
    }


    public function getOnlineDevices($serverId)
    {
        $onlineDevices = $this->server->getDataById("getOnlineDevices", "?serverId=$serverId");
        return $onlineDevices->object;
    }


    public function progress($serverUrl)
    {

        $devices = $this->server->requestedDataWithoutApiUrl($serverUrl);
        return $devices;
    }

    public function redirectWithMessage($checker, $url, $message)
    {
        if (isset($checker->ok) && !empty($checker->ok))
            if ($checker->ok) {
                return redirect($url)->with("message", $checker->message);
            } else {
                $message = "Something wrong!! try again later";
                return redirect($url)->with("message", $message);
            }
        else
            return redirect()->back()->with("message", $checker->message);
    }

    /**
     * @return \stdClass
     */
    public function getSyncStreamingServers(): \stdClass
    {
        $getSyncStreamingServers = $this->server->getAllData("syncStreamingServers");

        return $getSyncStreamingServers;
    }

    public function getAllConfigurations()
    {
        $getSyncStreamingServers = $this->config->getAllData("getAllConfigurations");

        return $getSyncStreamingServers;
    }

    public function postAllConfigurations($data)
    {
        if (isset($data->myConfig) && !empty($data->myConfig)) {
            $data = $data->myConfig;
        } else {
            $data = $data->config;
        }
        $myData = [];
        $index = 0;
        foreach ($data as $key => $value) {
            foreach ($value as $myKey => $myValue) {
                $myData[$index]['id'] = $key;
                $myData[$index]['configKey'] = $myKey;
                $myData[$index]['configValue'] = $myValue;
                $myData[$index]['configType'] = 'boolean';
                $index++;
            }
        }
        $requestConfig = $this->config->insertData("updateConfiguration", [
            GuzzleHttp\RequestOptions::JSON => $myData]);

        return $requestConfig;

    }

}