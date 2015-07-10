<?php

namespace Orbit\Machine\Auth;

use Orbit\Machine\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function register()
    {
        $config = $this->getConfig('auth')->model;
        //var_dump($config); die;
        //return new Auth(new $config);
    }
}
