<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 09/01/18
 * Time: 17:20
 */

namespace App\Apis;


class Dashboard
{
    private $baseUrl = "/codes";
    private $codeApis;

    function __construct(ApiIntegration $api)
    {
        $api->baseUrl = $this->baseUrl ;
        $this->codeApis = $api ;
    }
}