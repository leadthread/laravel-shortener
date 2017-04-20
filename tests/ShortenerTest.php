<?php

namespace LeadThread\Shortener\Tests;

use Cache;
use LeadThread\Bitly\Bitly;
use LeadThread\GoogleShortener\Google;
use LeadThread\Bitly\Exceptions\BitlyRateLimitException;
use LeadThread\Shortener\Drivers\Bitly as BitlyDriver;
use LeadThread\Shortener\Drivers\Google as GoogleDriver;
use LeadThread\Shortener\Exceptions\ShortenerException;
use LeadThread\Shortener\Facades\Shortener as ShortenerFacade;
use LeadThread\Shortener\Rotators\Account\Bitly as BitlyRotator;
use LeadThread\Shortener\Rotators\Account\Google as GoogleRotator;
use LeadThread\Shortener\Rotators\Service\Rotator as ServiceRotator;
use LeadThread\Shortener\Shortener;

class ShortenerTest extends TestCase
{

    protected $drivers = ['google', 'bitly'];

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('cache.default', 'array');
    }

    public function testShortenerFacade()
    {
        $shortener = ShortenerFacade::getFacadeRoot();
        $this->assertInstanceOf(Shortener::class, $shortener);
    }

    public function testItCreatesAnInstanceOfShortener()
    {
        $shortener = new Shortener();
        $this->assertInstanceOf(Shortener::class, $shortener);
    }

    public function testThrowsExceptionWhenUsingUnsupportedDriver()
    {
        $this->setExpectedException(ShortenerException::class);
        $this->app['config']->set('shortener.driver', 'foobar');
        $shortener = new Shortener();
    }

    public function testCacheDisabled()
    {
        $this->app['config']->set('shortener.cache.enabled', false);
        foreach ($this->drivers as $driver) {
            Cache::flush();
            $shortener = $this->getRotatorThatWillSucceedTheFirstTime("http://bar.com/", $driver);
            $url = $shortener->shorten("https://foo.bar");
            $this->assertEquals($url, "http://bar.com/");
        }
    }
    
    public function testShortenMethodReturnsValue()
    {
        foreach ($this->drivers as $driver) {
            Cache::flush();
            $shortener = $this->getRotatorThatWillSucceedTheFirstTime("http://bar.com/", $driver);
            $url = $shortener->shorten("https://foo.bar");
            $this->assertEquals($url, "http://bar.com/");
        }
    }

    public function testShortenMethodReturnsValueWhenCacheDriverIsFile()
    {
        $this->app['config']->set('cache.default', 'file');
        foreach ($this->drivers as $driver) {
            Cache::flush();
            $shortener = $this->getRotatorThatWillSucceedTheFirstTime("http://bar.com/", $driver);
            $url = $shortener->shorten("https://foo.bar");
            $this->assertEquals($url, "http://bar.com/");
        }
    }

    public function testShortenMethodReturnsValueWhenTheRotatorFailsForTheFirstTime()
    {
        foreach ($this->drivers as $driver) {
            Cache::flush();
            $shortener = $this->getRotatorThatWillFailTheFirstTime("http://bar.com/", $driver);
            $url = $shortener->shorten("https://foo.bar");
            $this->assertEquals($url, "http://bar.com/");
        }
    }

    public function testShortenMethodReturnsValueWhenItFailTheFirstServiceAndSucceedOnTheSecondService()
    {
        Cache::flush();
        $shortener = $this->getRotatorThatWillFailTheFirstServiceAndSucceedOnTheSecondService("http://bar.com/");
        $url = $shortener->shorten("https://foo.bar");
        $this->assertEquals($url, "http://bar.com/");
    }

    public function testShortenMethodThrowsExceptionWhenItFailsAllTimesBitly()
    {
        $this->setExpectedException(ShortenerException::class);
        $shortener = $this->getRotatorThatWillFailAllTimes("http://bar.com/", 'bitly');
        $url = $shortener->shorten("https://foo.bar");
    }

    public function testShortenMethodThrowsExceptionWhenItFailsAllTimesGoogle()
    {
        $this->setExpectedException(ShortenerException::class);
        $shortener = $this->getRotatorThatWillFailAllTimes("http://bar.com/", 'google');
        $url = $shortener->shorten("https://foo.bar");
    }

    /*========================================
    =            Helper functions            =
    ========================================*/

    protected function getRotatorThatWillFailTheFirstServiceAndSucceedOnTheSecondService($data)
    {
        $services = [];

        $bitlyMocks = [];
        $bitlyMocks[] = $this->buildMock('bitly', false, 'token1', 'bit.ly/asdf', 'once');
        $bitlyMocks[] = $this->buildMock('bitly', false, 'token2', 'bit.ly/asdf', 'once');
        $bitlyMocks[] = $this->buildMock('bitly', false, 'token3', 'bit.ly/asdf', 'once');

        $googleMocks = [];
        $googleMocks[] = $this->buildMock('google', true, 'token1', $data, 'once');
        $googleMocks[] = $this->buildMock('google', false, 'token2', $data, 'never');
        $googleMocks[] = $this->buildMock('google', false, 'token3', $data, 'never');

        $services[] = new BitlyDriver(new BitlyRotator($bitlyMocks));
        $services[] = new GoogleDriver(new GoogleRotator($googleMocks));
        
        return new Shortener(new ServiceRotator($services));
    }

    protected function getRotatorThatWillFailAllTimes($data, $driver = 'bitly')
    {
        $services = [];

        $mocks = [];
        $mocks[] = $this->buildMock($driver, false, 'token1', $data, 'once');
        $mocks[] = $this->buildMock($driver, false, 'token2', $data, 'once');
        $mocks[] = $this->buildMock($driver, false, 'token3', $data, 'once');

        if ($driver === 'bitly') {
            $services[] = new BitlyDriver(new BitlyRotator($mocks));
        } else if ($driver === 'google') {
            $services[] = new GoogleDriver(new GoogleRotator($mocks));
        }
        
        return new Shortener(new ServiceRotator($services));
    }

    protected function getRotatorThatWillFailTheFirstTime($data, $driver = 'bitly')
    {
        $services = [];

        $mocks = [];
        $mocks[] = $this->buildMock($driver, false, 'token1', $data, 'once');
        $mocks[] = $this->buildMock($driver, true, 'token2', $data, 'once');
        $mocks[] = $this->buildMock($driver, true, 'token3', $data, 'never');

        if ($driver === 'bitly') {
            $services[] = new BitlyDriver(new BitlyRotator($mocks));
        } else if ($driver === 'google') {
            $services[] = new GoogleDriver(new GoogleRotator($mocks));
        }
        
        return new Shortener(new ServiceRotator($services));
    }

    protected function getRotatorThatWillSucceedTheFirstTime($data, $driver = 'bitly')
    {
        $services = [];

        $mocks = [];
        $mocks[] = $this->buildMock($driver, true, 'token1', $data, 'once');
        $mocks[] = $this->buildMock($driver, true, 'token2', $data, 'never');
        $mocks[] = $this->buildMock($driver, true, 'token3', $data, 'never');

        if ($driver === 'bitly') {
            $services[] = new BitlyDriver(new BitlyRotator($mocks));
        } else if ($driver === 'google') {
            $services[] = new GoogleDriver(new GoogleRotator($mocks));
        }
        
        return new Shortener(new ServiceRotator($services));
    }

    protected function buildMock($driver, $succeed, $token, $data, $freq = 'once')
    {
        if ($driver === 'bitly') {
            $mock = $this->getMock(Bitly::class, [], [$token]);
        } else if ($driver === 'google') {
            $mock = $this->getMock(Google::class, [], [$token]);
        }

        if ($succeed) {
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
