<?php

namespace Zenapply\Shortener\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();        
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app)
    {
        return ['Zenapply\Shortener\ShortenerServiceProvider'];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageAliases($app)
    {
        return [
            'Zenapply\Shortener\Facades\Shortener',
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('shortener', [
            'driver' => 'bitly',
            'cache' => [
                'enabled'  => true,
                'duration' => 1440,
            ],
            'bitly' => [
                ['username' => 'USERFOOBAR1', 'password' => 'pass1234'],
                ['username' => 'USERFOOBAR2', 'password' => 'pass1234'],
                ['username' => 'USERFOOBAR3', 'password' => 'pass1234'],
                ['username' => 'USERFOOBAR4', 'password' => 'pass1234'],
            ], 
        ]);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}