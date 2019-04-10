<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 4/5/18
 * Time: 3:08 PM
 */

namespace App\Apis;
use Illuminate\Support\Facades\Redis;

class UsersApi
{
    private $baseUrl = "/users";
    private $user;

    function __construct(ApiIntegration $coreClass)
    {
        $coreClass->baseUrl = $this->baseUrl;
        $this->user = $coreClass;
    }

    public function checkRequest($request)
    {
        $myRequest = $request->fullUrl();

        if (!(str_contains($myRequest, 'page')) && !(str_contains($myRequest, 'num')) ) {
            $data =  $this->getAllPanelUsers();
            $redis = Redis::connection();
            $redis->set('users_api', json_encode($data));
            return   $data ;
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
        if (!($redis->exists('users_api'))) {
            $data = $this->getAllPanelUsers();
            $redis->set('users_api', json_encode($data));
        }
        $response = $redis->get('users_api');
        $response = json_decode($response);
        Redis::expire('users_api', config("app.expire_redis"));

        return $response;
    }

    /**
     * @param $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function UsersPaginationPages($request)
    {
        $response = $this->checkRequest($request);
        //paginate
        $users =  $this->user->paginationPages($response, $request);
        $users->setPath('/admin/users_api');

        return $users;
    }

    /**
     * @return array
     */
    public function getAllPanelUsers(): array
    {
        $users =  $this->user->getAllData("getAllPanelUsers");
        $users = $users->object;

        return $users;
    }

    public function createAllPanelUsersList()
    {
        $users = [];
        $getUsers = $this->getAllPanelUsers();
        foreach ($getUsers as $user) {
            $users[$user->id] = $user->name;
        }
        return $users;
    }

    public function insertUserApi($apiKey, $name)
    {
        $usersAPIS = $this->user->insertDataGet("createPanelUser", "?name=$name&api=$apiKey");
        return $usersAPIS;
    }

}