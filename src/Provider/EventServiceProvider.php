<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $serviceName = 'eventsManager';

    public function register()
    {
        $manager = new \Phalcon\Events\Manager;

        return $manager;
    }
}