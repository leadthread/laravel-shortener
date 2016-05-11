<?php

namespace Zenapply\Shortener\Rotators;

use Zenapply\Shortener\Exceptions\ShortenerException;
use Zenapply\Bitly\Exceptions\BitlyRateLimitException;

class Bitly extends Rotator
{
    protected function tryShortener($driver, $url, $encode)
    {
        $short = false;
        try{
            $short = $driver->shorten($url, $encode);
        } catch (BitlyRateLimitException $e) {
            
        }
        return $short;
    }
}