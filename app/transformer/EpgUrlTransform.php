<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 5/10/18
 * Time: 4:23 PM
 */

namespace App\transformer;


class EpgUrlTransform
{
    public function transform($channels)
    {
        $channels = $this->getDateSorted($channels);

        $i = 0;
        foreach ($channels as $channel) {

            if ($channel->createdAt) {
                $channel->createdAt = date("d M Y H:i:s", (intval($channel->createdAt / 1000)));
            }
            if ($channel->expiryDate) {
                $channel->expiryDate = date("d M Y H:i:s", (intval($channel->expiryDate / 1000)));
            }
            $i++;
        }

        return $channels;
    }

    public function getDateSorted($data)
    {
        usort($data, function ($a, $b) {
            return ($a->createdAt< $b->createdAt);
        });

        return $data;
    }
}