<?php

namespace LeadThread\Shortener\Drivers;

use LeadThread\GoogleShortener\Google as GoogleDriver;
use LeadThread\Shortener\Interfaces\UrlShortener;
use LeadThread\Shortener\Rotators\Account\Google as GoogleRotator;

class Google implements UrlShortener
{
    protected $config;
    protected $rotator;

    public function __construct(GoogleRotator $rotator = null)
    {
        $this->config = config('shortener.accounts.google');

        if(!$rotator instanceof GoogleRotator){
            $drivers = [];

            foreach ($this->config as $c) {
                $drivers[] = new GoogleDriver($c['token']);
            }

            $rotator = new GoogleRotator($drivers);
        }
        
        $this->rotator = $rotator;
    }

    public function shorten($url, $encode = true){
        return $this->rotator->shorten($url, $encode);
    }
}