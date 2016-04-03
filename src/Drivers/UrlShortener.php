<?php

namespace Zenapply\Shortener\Drivers;

interface UrlShortener
{
    public function shorten($url, $encode);
}