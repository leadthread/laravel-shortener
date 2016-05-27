<?php

namespace Zenapply\Shortener\Rotators\Account;

use Exception;
use Zenapply\GoogleShortener\Google as GoogleDriver;
use Zenapply\Shortener\Exceptions\ShortenerException;

class Google extends Rotator
{
    protected function handle($driver, $url, $encode)
    {
        $short = false;
        if(!$driver instanceof GoogleDriver){
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