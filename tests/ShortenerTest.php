<?php

namespace Zenapply\Shortener\Tests;

use Zenapply\Bitly\Bitly;
use Zenapply\Bitly\Exceptions\BitlyRateLimitException;
use Zenapply\Shortener\Drivers\Bitly as BitlyDriver;
use Zenapply\Shortener\Exceptions\ShortenerException;
use Zenapply\Shortener\Facades\Shortener as ShortenerFacade;
use Zenapply\Shortener\Rotators\Bitly as BitlyRotator;
use Zenapply\Shortener\Shortener;

class ShortenerTest extends TestCase
{
    public function testShortenerFacade(){
        $shortener = ShortenerFacade::getFacadeRoot();
        $this->assertInstanceOf(Shortener::class,$shortener);
    }

    public function testItCreatesAnInstanceOfShortener(){
        $shortener = new Shortener();
        $this->assertInstanceOf(Shortener::class,$shortener);
    }

    public function testThrowsExceptionWhenUsingUnsupportedDriver(){
        $this->setExpectedException(ShortenerException::class);
        $this->app['config']->set('shortener.driver', 'foobar');
        $shortener = new Shortener();
    }

    public function testCacheDisabled(){
        $this->app['config']->set('shortener.cache.enabled', false);
        $shortener = $this->getRotatorThatWillSucceedTheFirstTime("http://bar.com/");
        $url = $shortener->shorten("https://foo.bar");
        $this->assertEquals($url, "http://bar.com/");
    }
    
    public function testShortenMethodReturnsValue(){
        $shortener = $this->getRotatorThatWillSucceedTheFirstTime("http://bar.com/");
        $url = $shortener->shorten("https://foo.bar");
        $this->assertEquals($url, "http://bar.com/");
    }

    public function testShortenMethodReturnsValueWhenTheRotatorFailsForTheFirstTime(){
        $shortener = $this->getRotatorThatWillFailTheFirstTime("http://bar.com/");
        $url = $shortener->shorten("https://foo.bar");
        $this->assertEquals($url, "http://bar.com/");
    }

    public function testShortenMethodThrowsExceptionWhenItFailsAllTimes(){
        $this->setExpectedException(ShortenerException::class);
        $shortener = $this->getRotatorThatWillFailAllTimes("http://bar.com/");
        $url = $shortener->shorten("https://foo.bar");
    }

    /*========================================
    =            Helper functions            =
    ========================================*/

    protected function getRotatorThatWillFailAllTimes($data, $driver = 'bitly'){
        if($driver==='bitly'){
            $mocks = [];
            $mocks[] = $this->buildMock(false,'token1',$data,'once');
            $mocks[] = $this->buildMock(false,'token2',$data,'once');
            $mocks[] = $this->buildMock(false,'token3',$data,'once');
            return new Shortener(new BitlyDriver(new BitlyRotator($mocks)));
        }
    }
    protected function getRotatorThatWillFailTheFirstTime($data, $driver = 'bitly'){
        if($driver==='bitly'){
            $mocks = [];
            $mocks[] = $this->buildMock(false,'token1',$data,'once');
            $mocks[] = $this->buildMock(true, 'token2',$data,'once');
            $mocks[] = $this->buildMock(true, 'token3',$data,'never');
            return new Shortener(new BitlyDriver(new BitlyRotator($mocks)));
        }
    }

    protected function getRotatorThatWillSucceedTheFirstTime($data, $driver = 'bitly'){
        if($driver==='bitly'){
            $mocks = [];
            $mocks[] = $this->buildMock(true,'token1',$data,'once');
            $mocks[] = $this->buildMock(true,'token2',$data,'never');
            $mocks[] = $this->buildMock(true,'token3',$data,'never');
            return new Shortener(new BitlyDriver(new BitlyRotator($mocks)));
        }
    }

    protected function buildMock($succeed,$token,$data,$freq = 'once'){
        $mock = $this->getMock(Bitly::class,[],[$token]);

        if($succeed){
            $mock->expects($this->$freq())
                 ->method('shorten')
                 ->will($this->returnValue($data));
        } else {
            $mock->expects($this->$freq())
                 ->method('shorten')
                 ->will($this->throwException(new BitlyRateLimitException));
        }

        return $mock;
    }
    
    /*=====  End of Helper functions  ======*/
}
