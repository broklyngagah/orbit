<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Tracy\Debugger;

class DispatcherServiceProvider extends ServiceProvider
{
    CONST CONTROLLER_NS = 'Controller';

    protected $serviceName = 'dispatcher';


    public function register()
    {

        $app = $this->getConfig('loader.default_namespace');
        $namespace = sprintf("%s\%s", $app, self::CONTROLLER_NS);

        $dispatcher = new MvcDispatcher();
        $dispatcher->setDefaultNamespace(
            $namespace
        );

        return $dispatcher;
    }


}