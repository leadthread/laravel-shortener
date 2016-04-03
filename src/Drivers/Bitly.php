<?php

namespace Zenapply\Shortener\Drivers;

use Zenapply\Bitly\Bitly as BitlyDriver;

class Bitly implements UrlShortener
{
    protected $config;
    protected $driver;

    public function __construct()
    {
        $this->config = config('shortener.bitly');
        $this->driver = new BitlyDriver($this->config['token']);
    }

    public function shorten($url, $encode = true){
        return $this->driver->shorten($url, $encode);
    }
}