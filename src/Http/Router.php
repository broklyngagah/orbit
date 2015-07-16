<?php

namespace Orbit\Machine\Http;

use Phalcon\Mvc\Router as PhalconRouter

class Router extends PhalconRouter
{

    $router = [];

    public function __construct($defaultRoute = false)
    {



        parent::__construct($defaultRoute);
    }
}