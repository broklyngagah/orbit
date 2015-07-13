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

    public function __construct(Bootstrap $bootstrap)
    {
        parent::__construct($bootstrap->getDI());

        $this->bootstrap = $bootstrap;
        $this->config = $bootstrap->getConfig();
    }

    public function run($uri = '')
    {
        $this->bootstrap->registerAutoload();

        $this->bootstrap->registerService();
        if($this->getConfig()['app']['debug']) {
            $this->getDI('error_handler');
        }

        //$this->setupEventManager();

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
}
