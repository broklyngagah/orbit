<?php


use Orbit\Machine\Bootstrap;
use Orbit\Machine\Config;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testConfigInstaceOfPhalconConfig()
    {
        $app = $this->getApp();
        $config = $app->getConfig();
        $this->assertInstanceOf('\Phalcon\Config' ,$config);
    }

    public function testServiceIsCorrectlyRegistered()
    {
        $services = [
            'request', 'response', 'eventsManager', 'crypt', 'router', 'view', 'session', 'basePath',
        ];

        $app = $this->getApp();
        $dir = $app->getBootstrap()->getConfig()->getDirectory() . DIRECTORY_SEPARATOR . '/router.php';
        $app->getDI()->setShared('router', function() use($dir) {
            return require_once $dir;
        });

        foreach ($services as $value) {
            $this->assertArrayHasKey($value, $app->getDI()->getServices());
        }
    }

    private function getApp()
    {
        $b = $this->getBootstrap();
        return new \Orbit\Machine\Application($b, ROOT_PATH);
    }

    private function getBootstrap()
    {
        $c = $this->getConfig();
        return (new Bootstrap($c))->start();
    }

    private function getConfig()
    {
        return (new Config)->setDirectory(ROOT_PATH . '/resources/configs')->build();
    }
}