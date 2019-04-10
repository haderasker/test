<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 24/12/17
 * Time: 12:01
 */

namespace App\Apis;

use App\Code;
use Illuminate\Support\Facades\Redis;

class Codes
{
    private $baseUrl = "/codes";
    private $apiUrl = "/api";
    private $codes;
    private $codesApis;

    function __construct(ApiIntegration $apiClass, CodeAPIS $codeApi, ApiIntegration $apis)
    {
        //set base url in new ApiIntegration
        $apiClass->baseUrl = $this->baseUrl;
        $this->codes = $apiClass;
        $this->codesApis = $codeApi;
        $apis->apiUrl = $this->apiUrl;
        $this->apis = $apis;
    }

    public function checkFilterStatus($allData, $active, $unActive)
    {
        $myFilterActive = [];
        $myFilterUnActive = [];
        if ($active != null) {
            foreach ($allData as $data) {
                if ($data->deviceId != '-') {
                    $myFilterActive[] = $data;
                }
            }
            return $myFilterActive;
        } elseif ($unActive != null) {
            foreach ($allData as $data) {
                if ($data->deviceId == '-') {
                    $myFilterUnActive[] = $data;
                }
            }
            return $myFilterUnActive;
        } elseif ($active != null && $unActive != null) {
            return $allData;
        } else {
            return $allData;
        }
    }


    /**
     * @param $request
     * @return mixed
     */
    public function checkRequest($request, $active, $unActive)
    {
        $myRequest = $request->fullUrl();

        if (!(str_contains($myRequest, 'page')) && !(str_contains($myRequest, 'num'))) {
            $codes = $this->getAllCodes();
            $data = $this->checkFilterStatus($codes, $active, $unActive);
            $redis = Redis::connection();
            $redis->set('codes', json_encode($data));
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
        if (!($redis->exists('codes'))) {
            $data = $this->getAllCodes();
            $redis->set('codes', json_encode($data));
        }
        $response = $redis->get('codes');
        $response = json_decode($response);
        Redis::expire('codes', config("app.expire_redis"));

        return $response;
    }

    /**
     * @param $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function codePaginationPages($request, $active = null, $unActive = null)
    {
        $response = $this->checkRequest($request, $active, $unActive);
        //paginate
        $codes = $this->codes->paginationPages($response, $request);
        $codes->setPath('/admin/codes');

        return $codes;
    }


    public function getAllCodes()
    {
        $codes = $this->codes->getAllData("getAllCodes");
        $codes = $codes->object;
        usort($codes, function ($a, $b) {
            return ($a->createdAt < $b->createdAt);
        });
        $codes = $this->modifyCodesForView($codes);

        return $codes;
    }

    public function generateCode($code)
    {
        $code = "?count=$code->count&packageId=$code->packageId&panelUserId=$code->panelUserId&userName=$code->userName";
        $requestedCode = $this->codes->insertDataGet("generate", $code);
        return $requestedCode;
    }

    public function activeGlobal($code, $mac)
    {
        $code = "?code=$code&mac=$mac";
        $requestedCode = $this->apis->insertDataGet("api/activate", $code);

        return $requestedCode;
    }

    public function activeCode($code, $mac, $api)
    {
        $code = "?code=$code&mac=$mac&apiClient=$api&dt=m";
        $requestedCode = $this->apis->insertDataGet("api/activate_device", $code);

        return $requestedCode;
    }

    public function apisList()
    {
        $apisList = $this->codesApis->createApisList();
        return $apisList;
    }

    public function redirectWithMessage($checker, $url, $message)
    {
        if (isset($checker->ok) && !empty($checker->ok))
            if ($checker->ok) {
                return redirect($url)->with("message", $message);
            } else {
                $message = "Something wrong!! try again later";
                return redirect($url)->with("message", $message);
            }
        else
            return redirect()->back()->with("message", $checker->message);
    }

    public function getDeviceCodes($deviceId)
    {
        $codes = $this->codes->getDataById("getDeviceCodes", "?deviceId=$deviceId");
        $codes = $this->modifyCodesForView($codes->object);
        return $codes;
    }

    private function modifyCodesForView($codes)
    {
        $index = 0;

        foreach ($codes as $code) {
            if (empty($code->start))
                $codes[$index]->start = "-";
            else
                $codes[$index]->start = date("d-m-Y h:i:s", $code->start / 1000);

            if (empty($code->end))
                $codes[$index]->end = "-";
            else
                $codes[$index]->end = date("d-m-Y h:i:s", $code->end / 1000);

            if (empty($code->createdAt))
                $codes[$index]->createdAt = "-";
            else
                $codes[$index]->createdAt = date("d-m-Y h:i:s", $code->createdAt / 1000);

            if (empty($code->deviceId))
                $codes[$index]->deviceId = "-";
            if (empty($code->userName))
                $codes[$index]->userName = "-";
            if (empty($code->password))
                $codes[$index]->password = "-";

            $index++;
        }
        return $codes;
    }

    public function getCodesToUser($id)
    {
        $codes = $this->codes->getDataById("getCodesByPanelUserId", "?panelUserId=$id");
        return $codes;
    }

    public function storeCodes($request)
    {
        $codesUser = $this->getCodesToUser($request->id);
        $codes = $codesUser->object;
        foreach ($codes as $code) {
            $myCode = [
                'code' => $code,
                'name' => $request->name,
            ];
            $created = Code::create($myCode);
        }

        return $created;
    }

    public function deleteCodes()
    {
        $deleted = Code::truncate();

        return $deleted;
    }
}