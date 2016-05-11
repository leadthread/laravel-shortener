<?php

namespace Zenapply\Shortener\Rotators;

use Exception;
use Zenapply\Shortener\Exceptions\ShortenerException;

class Bitly extends Rotator
{
    protected function handle($driver, $url, $encode)
    {
        $short = false;
        try{
            $short = $driver->shorten($url, $encode);
        } catch (Exception $e) {
            // Ignore exceptions so the rotator will attempt the next driver in the list
        }
        return $short;
    }
}