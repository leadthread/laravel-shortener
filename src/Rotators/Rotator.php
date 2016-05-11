<?php

namespace Zenapply\Shortener\Rotators;

use Zenapply\Shortener\Interfaces\UrlShortener;
use Zenapply\Shortener\Exceptions\ShortenerException;

abstract class Rotator implements UrlShortener
{
    protected $drivers;

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    abstract protected function handle($driver, $url, $encode);

    public function shorten($url, $encode = true)
    {
        $short = false;

        foreach ($this->drivers as $driver) {
            if(!is_string($short)){
                $short = $this->handle($driver, $url, $encode);
            }
        }

        if(is_string($short)){
            return $short;
        } else {
            throw new ShortenerException("Could not shorten url!");
        }
    }
}