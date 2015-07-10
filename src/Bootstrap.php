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

use Phalcon\DI\FactoryDefault;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as EventManager;
use Phalcon\Loader;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Events\EventsAwareInterface;

/**
 * Class Bootstrap
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Bootstrap implements InjectionAwareInterface, EventsAwareInterface
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
     * Config dump format.
     * @var array
     */
    protected $configDump;

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
     * Application base / root pah.
     * @var string
     */
    protected $basePath;

    /**
     * @param \Orbit\Machine\Config $config
     * @param \Phalcon\DiInterface  $di
     * @internal param bool $debug
     */
    public function __construct(Config $config, DiInterface $di = null, $basePath = null)
    {
        if(is_null($di)) {
            $this->setDI(new FactoryDefault);
        } else {
            $this->setDI($di);
            $this->isInjected = true;
        }

        $this->config = $config;
        $this->configDump = $this->config->dump();
        $this->configDir = $this->config->getDirectory();
        $this->setBasePath($basePath);
        $this->setEventsManager(new EventManager);
    }

    /**
     * Register all service to phalcon dependency injection
     *
     * @return mixed
     */
    public function registerService()
    {
        if($this->isInjected && ! preg_match('/cli/', php_sapi_name()) ) {
            return;
        }

        $config = $this->configDump;

        $serviceList = $config->app->services;

        $di = $this->di;

        foreach($serviceList as $name => $service) {
            $this->di[$name] = function () use ($service, $di, $config) {
                return (new $service($di, $config))->register();
            };

            //$this->di[$name] = (new $service($di, $config))->register();
        }

        return;
    }

    /**
     * Register the autoload.
     *
     * @return mixed
     */
    public function registerAutoload()
    {
        $config = $this->configDump->loader->namespaces->toArray();

        $loader = new Loader;
        $loader->registerNamespaces($config)
               ->register();

        return;
    }

    /**
     * Set the base path of application and inject to DI service container.
     *
     * @param string $basePath
     *
     * @return mixed
     */
    protected function setBasePath($basePath)
    {
        if(is_null($basePath)) return $this;

        $this->di->setShared('basePath', function() use ($basePath) {
            return $basePath;
        });

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

    /**
     * Register all event listener from config.
     *
     * @return mixed
     */
    public function registerEvents()
    {
        $listeners = $this->configDump->event;

        foreach($listeners as $name => $listener) {
            $this->eventManager->attach($name, new $listener);
        }

        return;
    }

    /**
     * Boot all registered loaders and services.
     *
     * @return mixed
     */
    protected function start()
    {
        $this->registerAutoload();
        $this->registerService();
        $this->registerEvents();
    }

    public function run($uri = null)
    {
        $this->start();

        $app = new \Phalcon\Mvc\Application($this->di);
        return $app->handle($uri);
    }

    /**
     * Set Events Manager for all application.
     *
     * @param EventManager $manager
     * @return $this
     */
    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->eventManager = $eventsManager;

        return $this;
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
     * Gets the value of config.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
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

    /**
     * Gets the value of configDump.
     *
     * @return \Phalcon\Config
     */
    public function getConfigDump()
    {
        return $this->configDump;
    }
}
