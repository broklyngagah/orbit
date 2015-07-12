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
use Orbit\Machine\Config\Config;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\DebugClassLoader;

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
     * @param \Orbit\Machine\Config $config
     * @param \Phalcon\DiInterface  $di
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

        $config = $this->config;

        $serviceList = $config['app']['services'];

        $di = $this->di;

        foreach($serviceList as $name => $service) {
            $this->di->setShared($name, function () use ($service, $di, $config) {
                return (new $service($di, $config))->register();
            });
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
        $config = $this->config['loader']['namespaces'];
        
        $loader = new Loader;
        $loader->registerNamespaces($config)
               ->register();

        return;
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

    protected function setupErrorHandler()
    {
        $di = $this->getDI();

        $this->getDI()->set('error_handler', function() use($di) {
            return new \Orbit\Machine\HandleException($di);
        }, false);

        return $this;
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
        $this->setupErrorHandler();
        
    }

    public function run($uri = null)
    {
        $this->start();

        if($this->config['app']['debug']) {
            $this->getDI('error_handler');
        } 

        $app = new \Phalcon\Mvc\Application($this->di);
        $app->useImplicitView('null' === $this->config['view']['default'] ? false : true);
        //$app->setEventsManager($this->getEventsManager());

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
}
