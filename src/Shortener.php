<?php

namespace Zenapply\Shortener;

use Exception;
use Illuminate\Support\Facades\Cache;
use Zenapply\Shortener\Drivers\Bitly;
use Zenapply\Shortener\Interfaces\UrlShortener;
use Zenapply\Shortener\Exceptions\ShortenerException;
use Zenapply\Shortener\Rotators\Service\Rotator as ServiceRotator;

/**
* The master class
*/
class Shortener 
{
    protected $config;
    protected $rotator;

    public function __construct(ServiceRotator $rotator = null)
    {
        $this->config = config('shortener');

        if(!$rotator instanceof ServiceRotator){
            $rotator = $this->getRotator();
        }
        
        $this->rotator = $rotator;
    }

    protected function getRotator(){
        $service = $this->config['driver'];
        

        if($service === null){
            $services = array_keys($this->config['accounts']);
        } else if(is_string($service)) {
            $services = [$service];
        } else {
            throw new Exception("Could not determine which services to use.");
        }

        $rotatorInstance = new ServiceRotator($services);

        return $rotatorInstance;
    }

    public function shorten($url, $encode = true){
        $rotator = $this->rotator;
        if($this->config['cache']['enabled'] === true){
            return Cache::tags('shortener')->remember($url, $this->config['cache']['duration'], function () use ($rotator, $url, $encode) {
                return $rotator->shorten($url, $encode);
            });
        } else {
            return $rotator->shorten($url, $encode);
        }
    }
}