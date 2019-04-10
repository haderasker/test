<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 12/12/17
 * Time: 12:28
 */

namespace App\Apis;

use Auth;
use GuzzleHttp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Config;


class ApiIntegration
{
    private $url;
    public $baseUrl;
    private $apiRequest;
    private $id ;

    /**
     * ApiIntegration constructor.
     *
     */
    function __construct()
    {
        $this->apiRequest = new GuzzleHttp\Client();
    }

    public function myData() {

        $id = Auth::user()->id;
        $oldConfig = Config::where('user_id' , $id)->first();

        if (empty( $oldConfig)) {
            $base_url = config("app.api_url")[0];
        }
        else {
            $base_url = $oldConfig->api_url ;
        }

        return $base_url ;
    }


    /**
     * @param $data
     * @param $request
     * @return LengthAwarePaginator
     */
    public function paginationPages($data, $request)
    {
        $num = 50;
        if (isset($request->num) && !empty($request->num)) {
            $num = $request->num;
        }
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($data);
        $perPage = $num;
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $dataPaginate = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);

        return $dataPaginate;
    }

    public function getAllData($requestedFunction)
    {
        $data = $this->requestedDataByGet($requestedFunction);
        return $data;
    }

    public function getDataById($requestedFunction, $requestedById)
    {
        $data = $this->requestedDataByGet($requestedFunction, $requestedById);
        return $data;
    }

    public function deleteLogsServerDevice($requestedFunction, $requestedById)
    {

        $data = $this->deleteServerDeviceLogsByGet($requestedFunction, $requestedById);
        return $data;
    }

    public function insertData($requestedFunction, $data)
    {

        $requestedData = $this->requestedDataByPost($requestedFunction, $data);
        return $requestedData;
    }

    public function insertAjaxData($requestedFunction, $data)
    {
        $requestedData = $this->requestedDataAjaxByPost($requestedFunction, $data);
        return $requestedData;
    }

    /**
     * @param $requestedFunction
     * @param $data
     * @return mixed
     */
    public function insertDataGet($requestedFunction, $data)
    {
        $requestedData = $this->requestedDataByGet($requestedFunction, $data);
        return $requestedData;
    }

    public function updateDataGet($requestedFunction, $data)
    {

        $requestedData = $this->requestedDataByGet($requestedFunction, $data);

        return $requestedData;
    }

    public function updateData($requestedFunction, $data)
    {
        $requestedData = $this->requestedDataByPost($requestedFunction, $data);
        return $requestedData;
    }

    public function deleteData($requestedFunction, $data)
    {
        $requestedData = $this->requestedDataByPost($requestedFunction, $data);

        return $requestedData;
    }

    /**
     * @param $requestedFunction
     * @param string $requestedById
     * @return mixed
     */
    private function requestedDataByGet($requestedFunction, $requestedById = "")
    {
        $getRequestedData = $this->apiRequest->get($this->myData()."$this->baseUrl/$requestedFunction$requestedById");
        $requestedData = json_decode($getRequestedData->getBody()->getContents());
        return $requestedData;
    }

    private function deleteServerDeviceLogsByGet($requestedFunction, $requestedById = "")
    {
        $getRequestedData = $this->apiRequest->get("$requestedFunction$requestedById");
        $requestedData = json_decode($getRequestedData->getBody()->getContents());
        return $requestedData;
    }

    private function requestedDataByPost($requestedFunction, $data)
    {

        $requestedData = $this->apiRequest->post($this->myData()."$this->baseUrl/$requestedFunction", $data);

        return json_decode($requestedData->getBody()->getContents());
    }

    private function requestedDataAjaxByPost($requestedFunction, $data)
    {
        $requestedData = $this->apiRequest->post($this->myData()."$this->baseUrl/$requestedFunction", $data);

        $responseReturn = $requestedData->getBody()->getContents();

        return json_decode($responseReturn, true);
    }

    public function getDateByUrl($url, $requestedFunction)
    {
        $getRequestedData = $this->apiRequest->get("$url/$requestedFunction");
        $requestedData = json_decode($getRequestedData->getBody()->getContents());
        return $requestedData;
    }

    public function requestedDataWithoutApiUrl($url)
    {
        $getRequestedData = $this->apiRequest->get($url, ["connection_timeout" => 3.14]);
        $requestedData = json_decode($getRequestedData->getBody()->getContents());
        return $requestedData;
    }

    public function getContentByUrl($requestedFunction)
    {
        $getRequestedData = $this->apiRequest->get($this->myData()."$this->baseUrl/$requestedFunction");
        $requestedData = $getRequestedData->getBody()->getContents();
        return $requestedData;
    }

    public function uploadFile($fileName, $content, $path)
    {
        $response = $this->apiRequest->post($this->myData()."/$path", [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => "$content",
                    'filename' => "$fileName.apk"
                ]
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    public function uploadFileApk($fileName, $content, $path)
    {
        $response = $this->apiRequest->post($this->myData()."/$path",
            [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => "$content",
                        'filename' => "$fileName.apk"
                    ]
                ]
            ]);
        $responseReturn = $response->getBody()->getContents();
        return json_decode($responseReturn, true);
    }


    public function uploadEPGFile($fileName, $content)
    {
        $response = $this->insertAjaxData('getAllLanguages', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => "$content",
                    'filename' => "$fileName"
                ]
            ]
        ]);
        return $response;
    }

}