<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Orbit\Machine\Tracy\ConfigBar;

class ConfigServiceProvider extends ServiceProvider
{

    protected $serviceName = 'config';

    public function register()
    {
        $config = $this->getConfig();

        return $config;
    }
}