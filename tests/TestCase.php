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
            'driver' => null,
            'cache' => [
                'enabled' => true,
                'duration' => 1440,
            ],
            'accounts' => [
                'google' => [
                    [
                        'token' => 'GOOGLE_SHORTENER_TOKEN_1' 
                    ],
                    [
                        'token' => 'GOOGLE_SHORTENER_TOKEN_2' 
                    ],
                    [
                        'token' => 'GOOGLE_SHORTENER_TOKEN_3' 
                    ],
                ],
                'bitly' => [
                    [
                        'username' => 'USERNAME', 
                        'password' => 'PASSWORD'
                    ],
                    [
                        'username' => 'USERNAME', 
                        'password' => 'PASSWORD'
                    ],
                    [
                        'username' => 'USERNAME', 
                        'password' => 'PASSWORD'
                    ],
                ], 
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