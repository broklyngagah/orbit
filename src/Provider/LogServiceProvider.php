<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{

    protected $serviceName = 'logger';

    public function register()
    {
        return new \Phalcon\Logger\Adapter\File($this->getConfig()->app->log);
    }
}