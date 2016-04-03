<?php

namespace Zenapply\Shortener;

use Illuminate\Support\Facades\Cache;
use Zenapply\Shortener\Drivers\Bitly;
use Zenapply\Shortener\Exceptions\ShortenerException;

/**
* The master class
*/
class Shortener 
{
    protected $config;
    protected $driver;

    public function __construct()
    {
        $this->config = config('shortener');
        $this->driver = $this->getDriver();
    }

    protected function getDriver(){
        $driver = $this->config['driver'];
        
        if($driver === 'bitly'){
            $driverInstance = new Bitly;  
        } else {
            throw new ShortenerException("That driver is not supported! ({$driver})");
        }

        return $driverInstance;
    }

    public function shorten($url, $encode = true){
        $driver = $this->driver;
        if($this->config['cache']['enabled'] === true){
            return Cache::tags('shortener')->remember($url, $this->config['cache']['duration'], function () use ($driver, $url, $encode) {
                return $driver->shorten($url, $encode);
            });
        } else {
            return $driver->shorten($url, $encode);
        }
    }
}