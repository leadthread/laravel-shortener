<?php

namespace Zenapply\Shortener\Rotators\Account;

use Exception;
use Zenapply\Bitly\Bitly as BitlyDriver;
use Zenapply\Shortener\Exceptions\ShortenerException;

class Bitly extends Rotator
{
    protected function handle($driver, $url, $encode)
    {
        $short = false;

        if(!$driver instanceof BitlyDriver){
            throw new Exception("Incorrect Driver! ".get_class($driver));
        }

        try{
            $short = $driver->shorten($url, $encode);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }

        return $short;
    }
}