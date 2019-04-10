<?php

namespace App\Http\Controllers\Admin;

use App\Apis\ApiIntegration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaginateController extends Controller
{
    private $api;

    function __construct(ApiIntegration $apiClass)
    {
        $this->api = $apiClass;
    }

    public function paginateNumber(Request $request)
    {
        $redirect = $request->headers->get('referer');
        $host = $request->headers->get('host');
        $myRedirect = str_replace($host,"",$redirect);
        $myNewRedirect = str_after($myRedirect , "http://");
        if(str_contains($myNewRedirect, '?')) {
            $myNewRedirect = str_before($myNewRedirect , '?');
        }
        return redirect($myNewRedirect .'?num=' .$request->paginate);

    }



}
