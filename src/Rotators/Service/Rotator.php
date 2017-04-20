<?php

namespace LeadThread\Shortener\Rotators\Service;

use Exception;
use LeadThread\Shortener\Interfaces\UrlShortener;
use LeadThread\Shortener\Exceptions\ShortenerException;
use LeadThread\Shortener\Drivers\Bitly;
use LeadThread\Shortener\Drivers\Google;

class Rotator implements UrlShortener
{
    protected $drivers = [];
    protected $error;

    public function __construct(array $services){
        foreach ($services as $service) {
            if($service instanceof UrlShortener){
                $this->drivers[] = $service;
            } else if(is_string($service)) {
                $this->drivers[] = $this->getDriver($service);
            } else {
                throw new Exception("Could not get driver! Incorrect datatype!");
            }
        }
    }

    protected function getDriver($service){
        switch($service){
            case 'google':
                return new Google();
            case 'bitly':
                return new Bitly();
            default:
                throw new ShortenerException("Service is not supported! ({$service})");
        }
    }

    public function shorten($url, $encode = true)
    {
        $short = false;

        foreach ($this->drivers as $driver) {
            if(!is_string($short)){
                try {
                    $short = $driver->shorten($url, $encode);
                } catch (Exception $e) {
                    $this->error = $e;
                }
            }
        }

        if(is_string($short)){
            return $short;
        } else {
            throw $this->error;
        }
    }
}