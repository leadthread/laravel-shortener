<?php

namespace LeadThread\Shortener\Rotators\Account;

use Exception;
use LeadThread\GoogleShortener\Google as GoogleDriver;
use LeadThread\Shortener\Exceptions\ShortenerException;

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