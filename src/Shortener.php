<?php

namespace LeadThread\Shortener;

use Exception;
use Illuminate\Support\Facades\Cache;
use LeadThread\Shortener\Drivers\Bitly;
use LeadThread\Shortener\Interfaces\UrlShortener;
use LeadThread\Shortener\Exceptions\ShortenerException;
use LeadThread\Shortener\Rotators\Service\Rotator as ServiceRotator;
use Illuminate\Cache\TaggableStore;

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

        if (!$rotator instanceof ServiceRotator) {
            $rotator = $this->getRotator();
        }
        
        $this->rotator = $rotator;
    }

    protected function getRotator()
    {
        $service = $this->config['driver'];
        

        if ($service === null) {
            $services = array_keys($this->config['accounts']);
        } else if (is_string($service)) {
            $services = [$service];
        } else {
            throw new Exception("Could not determine which services to use.");
        }

        $rotatorInstance = new ServiceRotator($services);

        return $rotatorInstance;
    }

    public function shorten($url, $encode = true)
    {
        $rotator = $this->rotator;
        if ($this->config['cache']['enabled'] === true) {
            return $this->getFromCache($url, $this->config['cache']['duration'], function () use ($rotator, $url, $encode) {
                return $rotator->shorten($url, $encode);
            });
        } else {
            return $rotator->shorten($url, $encode);
        }
    }

    protected function getFromCache($key, $time, $fn)
    {
        $cache = Cache::driver(Cache::getDefaultDriver());
        if ($cache instanceof TaggableStore) {
            $cache->tags('shortener');
        }
        return $cache->remember($key, $time, $fn);
    }
}
