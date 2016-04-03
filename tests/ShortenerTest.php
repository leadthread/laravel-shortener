<?php

namespace Zenapply\Shortener\Tests;

use Zenapply\Shortener\Shortener;
use Zenapply\Shortener\Exceptions\ShortenerException;

class ShortenerTest extends TestCase
{
    public function testItCreatesAnInstanceOfShortener(){
        $r = new Shortener();
        $this->assertInstanceOf(Shortener::class,$r);
    }
}
