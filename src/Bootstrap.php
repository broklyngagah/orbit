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

use Orbit\Machine\Config\Config;
use Phalcon\DI\FactoryDefault;
use Phalcon\DiInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Loader;
use Phalcon\Mvc\Application;

/**
 * Class Bootstrap
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Bootstrap
{
    /**
     * Load the injectable trait.
     */
    use InjectableTrait;

    /**
     * Config builder.
     * @var Config
     */
    protected $config;


    /**
     * Configuration files directory.
     * @var string
     */
    protected $configDir;

    /**
     * Phalcon events manager.
     * @var \Phalcon\Events\Manager
     */
    protected $eventManager;


    /**
     * Identifier that $di is injected from argument or not.
     * @var bool $isInjected
     */
    protected $isInjected = false;

    /**
     * List of services.
     * @var array
     */
    protected $services;

    /**
     * @param Config $config
     * @param \Phalcon\DiInterface $di
     */
    public function __construct(Config $config, DiInterface $di = null)
    {
        if(is_null($di)) {
            $this->setDI(new FactoryDefault);
        } else {
            $this->setDI($di);
            $this->isInjected = true;
        }

        $this->config = $config;

        $this->configDir = $config->getDirectory();
    }


    /**
     * Get base path.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->config['base_path'];
    }

    public function run($uri = '')
    {
        $this->boot();

        if($this->getConfig()['app']['debug']) {
            $this->getDI('error_handler');
        }

        return $this->applicationSetup($uri);
    }

    /**
     * Boot all registered loaders and services.
     *
     * @return mixed
     */
    protected function boot()
    {
        $this->registerAutoload();
        $this->registerService();
        $this->setupErrorHandler();
    }

    /**
     * Register the autoload.
     *
     * @return mixed
     */
    public function registerAutoload()
    {
        $config = $this->config['loader']['namespaces'];

        $loader = new Loader;
        $loader->registerNamespaces($config)
            ->register();

        return;
    }

    /**
     * Register all service to phalcon dependency injection
     *
     * @return mixed
     */
    public function registerService()
    {
        if($this->isInjected && !preg_match('/cli/', php_sapi_name())) {
            return $this;
        }

        $config = $this->config;

        $serviceList = $config['app']['services'];

        $di = $this->getDI();

        foreach($serviceList as $name => $service) {
            $this->di->setShared($name, function () use ($service, $di, $config) {
                return (new $service($di, $config))->register();
            });
        }

        return $this;
    }

    /**
     * Setup error handler for this application
     * @return self
     */
    public function setupErrorHandler()
    {
        $di = $this->getDI();

        $this->getDI()->set('error_handler', function () use ($di) {
            return new HandleException($di);
        }, true);

        return $this;
    }

    /**
     * Gets the value of config.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Setup the phalcon application.
     *
     * @param null $uri
     * @return string
     */
    protected function applicationSetup($uri = null)
    {
        $app = new Application($this->di);
        $app->useImplicitView('null' === $this->config['view']['default'] ? false : true);

        // set event manager for application
        $eventManager = $app->getDI()->getShared('eventsManager');

        $this->setEventsManager($eventManager);
        $this->registerEvents();

        /*
         * TODO This is bug on phalcon 2.0.4
         */
        $app->setEventsManager($this->getEventsManager());

        return $app->handle($uri)->getContent();
    }

    /**
     * Set Events Manager for all application.
     *
     * @param ManagerInterface $eventsManager
     * @return $this
     * @internal param EventManager $manager
     */
    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->eventManager = $eventsManager;

        return $this;
    }

    /**
     * Register all event listener from config.
     *
     * @return mixed
     */
    public function registerEvents()
    {
        $listeners = $this->config['event'];

        foreach($listeners as $name => $listener) {
            $listener = '\\' . $listener;
            $this->eventManager->attach($name, new $listener);
        }

        return;
    }

    /**
     * Gets the value of eventManager.
     *
     * @return mixed
     */
    public function getEventsManager()
    {
        return $this->eventManager;
    }

    /**
     * Gets the value of configDir.
     *
     * @return mixed
     */
    public function getConfigDir()
    {
        return $this->configDir;
    }
}
