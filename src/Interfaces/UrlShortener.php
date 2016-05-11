<?php

namespace Zenapply\Shortener\Interfaces;

interface UrlShortener
{
    public function shorten($url, $encode);
}