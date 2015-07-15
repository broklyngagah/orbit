<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Orbit\Machine\Session\Connector\FilesConnector;
use Orbit\Machine\Session\Manager;

class SessionServiceProvider extends ServiceProvider
{

    protected $serviceName = 'session';

    private $defaults = [
        'files',
        'redis',
    ];

    public function register()
    {
        $manager = new Manager($this->getConfig()->toArray());

        $this->registerConnectors($manager);

        return $manager->connection();
    }

    private function registerConnectors($manager)
    {
        foreach($this->defaults as $connector) {
            $connector = ucfirst($connector);
            $this->{"register{$connector}Connector"}($manager);
        }
    }

    private function registerFilesConnector($manager)
    {
        $manager->addConnector('files', function () {
            return new FilesConnector;
        });
    }

    private function registerRedisConnector($manager)
    {
        $manager->addConnector('redis', function () {
            return new RedisConnector;
        });
    }

    private function registerFlash()
    {
        /*$this->getDI()->set('flash', function () {
            return new \Phalcon\Flash\Direct([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
            ]);
        });


        $this->getDI()->set('flashSession', function () {
            return new \Phalcon\Flash\Session([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
            ]);
        });*/

    }
}
