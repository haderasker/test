<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Apis\Statistic;
use App\transformer\StatisticTransform;

class StatisticController extends Controller
{
    private $statistic , $transform;

    function __construct(Statistic $statistic , StatisticTransform $transform)
    {
        $this->statistic = $statistic;
        $this->transform = $transform;
    }

    public function index(Request $request) {

        $allStatistic =  $this->statistic->getAllStatistic($request->ipUrl)->object;
        $allData = $this->transform->transform($allStatistic);

        return view('admin.statistic.index' ,compact('allData'));
    }
}
