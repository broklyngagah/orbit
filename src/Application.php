<?php

/*
 * This file is part of the Orbit Machine Package.
 *
 * (c) Pieter Lelaona <broklyn.gagah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Orbit\Machine;

use Phalcon\DI;
use Phalcon\Mvc\Application as PhalconApp;
use Orbit\Machine\Auth\Auth as AuthComponent;

/**
 * Class Application
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Application extends PhalconApp
{

    const VERSION = '1.0-beta';

    /** @var Bootstrap $bootstrap */
    protected $bootstrap;

    /** @var \Phalcon\Config $config */
    protected $config;

    /** @var string $basePath */
    protected $basePath;

    public function __construct(Bootstrap $bootstrap, $basePath = null)
    {
        parent::__construct($bootstrap->getDI());

        $this->bootstrap = $bootstrap;
        $this->config = $bootstrap->getConfigDump();
        $this->setBasePath($basePath);
    }

    public function run($uri = '')
    {
        $this->bootstrap->registerAutoload();

        $this->bootstrap->registerService();

        //$this->setupAuth();

        $this->setupEventManager();

        return $this->handle($uri)->getContent();
    }

    /**
     * Setup all event.
     *
     * @return mixed
     */
    private function setupEventManager()
    {
        // set new instance of events manager
        $eventManager = $this->getDI()->getShared('eventsManager');

        // we must to set all event listener into eventsManager service.
        // It use same eventsManger between Application and Bootstrap class.
        $this->bootstrap->setEventManager($eventManager);
        $this->setEventsManager($eventManager);

        // registering all events listener to default eventsManager service.
        $this->bootstrap->registerEvents();

        return $this;
    }

    /**
     * Get bootstrap class instance.
     * @return Bootstrap
     */
    public function getBootstrap()
    {
        return $this->bootstrap;
    }

    /**
     * Gets the value of config.
     *
     * @return \Phalcon\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the base path of application and inject to DI service container.
     *
     * @param string $basePath
     *
     * @return mixed
     */
    public function setBasePath($basePath)
    {
        if(is_null($basePath)) return $this;

        $this->bootstrap->di->setShared('basePath', function () use ($basePath) {
            return $basePath;
        });

        return $this;
    }

    public function setupAuth()
    {
        $eventsManager = $this->bootstrap->di->get('eventsManager');
        $eventsManager->attach('dispatch:beforeDispatch', new AuthComponent, 50);

        $dispatcher = $this->bootstrap->di->get('dispatcher');
        $dispatcher->setEventsManager($eventsManager);

        return $this;
    }

    /**
     * Get base path.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
}
