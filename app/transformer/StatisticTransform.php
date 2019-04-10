<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 4/15/18
 * Time: 2:46 PM
 */

namespace App\transformer;


class StatisticTransform
{
    public function transform($allStatistic)
    {

        $data = [];
        $i = 0;
        foreach ($allStatistic as $statistic) {
            $data[$i]['id'] = $statistic->id;
            $data[$i]['deviceId'] = $statistic->deviceId;
            $data[$i]['streamId'] = $statistic->streamId;
            $data[$i]['streamName'] = $statistic->streamName;
            $data[$i]['ip'] = $statistic->ip;
            $data[$i]['country'] = $statistic->country;
            $data[$i]['isp'] = $statistic->isp;
            $data[$i]['org'] = $statistic->org;
            $data[$i]['region'] = $statistic->region;
            if ($statistic->tsStartTime) {
                $data[$i]['tsStartTime'] = date("d M Y H:i:s", (intval($statistic->tsStartTime / 1000)));
            } else {
                $data[$i]['tsStartTime'] = $statistic->tsStartTime;
            }
            if ($statistic->tsEndTime) {
                $data[$i]['tsEndTime'] = date("d M Y H:i:s", (intval($statistic->tsEndTime / 1000)));
            } else {
                $data[$i]['tsEndTime'] = $statistic->tsEndTime;
            }
            $data[$i]['tsSize'] = $this->number($statistic->tsSize);
            $data[$i]['tsDuration'] = $this->formatMilliseconds($statistic->tsDuration)  . ' Sec';
            $data[$i]['bandwidth'] = $this->bandwidthCalc($statistic->tsSize , $this->formatMilliseconds($statistic->tsDuration)) . 'Bps' ;
            $data[$i]['tsIndex'] = $statistic->tsIndex;
            $data[$i]['userAgent'] = $statistic->userAgent;
            $data[$i]['cloudSource'] = $statistic->cloudSource;
            $data[$i]['requestUrl'] = $statistic->requestUrl;
            $i++;

        }

        return $data;
    }

    function number($number)
    {
        $number = preg_replace('/[^\d]+/', '', $number);
        if (!is_numeric($number)) {
            return 0;
        }
        if ($number < 1000) {
            return $number;
        }
        $unit = intval(log($number, 1000));
        $units = [' ', ' K', ' M', ' B', ' T', ' Q'];
        if (array_key_exists($unit, $units)) {
            return sprintf('%s%s', rtrim(number_format($number / pow(1000, $unit), 1), '.0'), $units[$unit]);
        }
        return $number;
    }

    function formatMilliseconds($milliseconds)
    {
        $seconds = $milliseconds  /1000;
         $time = round($seconds, 3);

        return $time;
    }

    function bandwidthCalc($size  , $time)
    {
        $bandwidthCalc = round($size / $time ) ;
        $bandwidth = $this->number($bandwidthCalc);

        return $bandwidth ;
    }

}