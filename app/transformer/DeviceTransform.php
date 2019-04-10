<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 3/29/18
 * Time: 2:06 PM
 */

namespace App\transformer;


class DeviceTransform
{
    public function deviceTransform($allLogs)
    {
        $data = [];
        $i = 0;
        foreach ($allLogs as $server) {
            foreach ($server->logs as $devices) {
                foreach ($devices->deviceStreamLogs as $log) {
                    $data[$i]['server_name'] = $server->streamingServer->name;
                    $data[$i]['server_url'] = $server->streamingServer->ipUrl;
                    $data[$i]['device_name'] = $devices->stream->name;
                    $data[$i]['icon'] = $devices->stream->icon;
                    $data[$i]['startStatus'] = $log->startStatus;
                    $data[$i]['endStatus'] = $log->endStatus;
                    $data[$i]['msg'] = $log->msg;
                    if ($log->startTime) {
                        $data[$i]['startTime'] = date("d M Y H:i:s", (intval($log->startTime / 1000)));
                    } else {
                        $data[$i]['startTime'] = $log->startTime;
                    }
                    if ($log->endTime) {
                        $data[$i]['endTime'] = date("d M Y H:i:s", (intval($log->endTime / 1000)));
                    } else {
                        $data[$i]['endTime'] = $log->endTime;
                    }
                    $data[$i]['duration'] = $log->duration;
                    $data[$i]['ip'] = $log->ip;
                    $i++;
                }
            }
        }
        return $this->getDateSorted($data);
    }

    public function getDateSorted($data)
    {
        usort($data, function ($a, $b) {
            return ($a['startTime'] < $b['startTime']);
        });

        return $data;
    }
}

