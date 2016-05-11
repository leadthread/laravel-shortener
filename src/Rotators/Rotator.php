<?php

namespace Zenapply\Shortener\Rotators;

use Zenapply\Shortener\Interfaces\UrlShortener;
use Zenapply\Shortener\Exceptions\ShortenerException;

abstract class Rotator implements UrlShortener
{
    public $retries = 1;
    protected $drivers;

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    abstract protected function tryShortener($driver, $url, $encode);

    public function shorten($url, $encode = true)
    {
        $short = false;

        for ($i=0; $i < $this->retries; $i++) { 
            foreach ($this->drivers as $driver) {
                if(!is_string($short)){
                    $short = $this->tryShortener($driver, $url, $encode);
                }
            }
        }

        if(is_string($short)){
            return $short;
        } else {
            throw new ShortenerException("Could not shorten url!");
        }
    }
}