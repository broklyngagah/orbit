<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Phalcon\Http\Request;

class RequestServiceProvider extends ServiceProvider
{

    protected $serviceName = 'request';

    public function register()
    {
        return new Request;
    }
}
