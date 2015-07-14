<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{

    protected $serviceName = 'logger';

    public function register()
    {
    	$config = $this->getConfig('app.log');
        return new \Phalcon\Logger\Adapter\File($config);
    }
}