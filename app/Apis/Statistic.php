<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 4/15/18
 * Time: 1:23 PM
 */

namespace App\Apis;


class Statistic
{
    private $baseUrl = "/statistics";
    private $statistic;

    function __construct(ApiIntegration $apiIntegration)
    {
        //set base url in new ApiIntegration
        $apiIntegration->baseUrl = $this->baseUrl;
        $this->statistic = $apiIntegration;
    }

    public function getAllStatistic($url) {
        $allStatistic = $this->statistic->getDateByUrl($url ,  $this->baseUrl . '/getAllStatistics');

        return $allStatistic;
    }

}