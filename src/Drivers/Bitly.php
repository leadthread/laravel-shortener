<?php

namespace Zenapply\Shortener\Drivers;

use Zenapply\Bitly\Bitly as BitlyDriver;

class Bitly implements UrlShortener
{
    protected $config;
    protected $shortener;

    public function __construct(BitlyDriver $shortener = null)
    {
        $this->config = config('shortener.bitly');

        if(!$shortener instanceof BitlyDriver){
            $this->shortener = new BitlyDriver($this->config['token']);
        }
        
        $this->shortener = $shortener;
    }

    public function shorten($url, $encode = true){
        return $this->shortener->shorten($url, $encode);
    }
}