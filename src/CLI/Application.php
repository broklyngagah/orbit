<?php

namespace Orbit\Machine\CLI;

use Orbit\Machine\Bootstrap;
use Orbit\Machine\CLI\Command;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use Orbit\Machine\Config\Config;

/**
 * Class Console Application
 * @package Orbit\Machine\CLI
 * @author  Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Application extends ConsoleApplication
{

    /**
     * Version of orbit sketelon.
     */
    const VERSION = '1.0.0-beta';

    /**
     * @var \Phalcon\DiInterface $di
     */
    protected $di;

    /**
     * @var Bootstrap $bootstrap
     */
    protected $bootstrap;

    /**
     * @var Config
     */
    protected $config;


    public function __construct(Bootstrap $bootstrap, $basePath)
    {
        parent::__construct();

        $this->bootstrap = $bootstrap;

        $this->config = $bootstrap->getConfig();

        // setup base path
        $this->setBasePath($basePath);

        $this->di = $bootstrap->getDI();

        $this->setName('Orbit Skeleton Console');

        $this->setVersion(self::VERSION);
    }

    /**
     * Gets the value of di.
     *
     * @return \Phalcon\DiInterface|mixed
     */
    public function getDI()
    {
        return $this->di;
    }

    public function add(SymfonyCommand $command)
    {
        if($command instanceof Command) {
            $command->setDI($this->di);
            $command->setOrbit($this);
        }

        return $this->addToParent($command);
    }

    /**
     * Add the command to the parent instance.
     *
     * @param  \Symfony\Component\Console\Command\Command $command
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function addToParent(SymfonyCommand $command)
    {
        return parent::add($command);
    }

    /**
     * Reoslve command
     *
     * @param string|mixed $command
     * @return SymfonyCommand
     */
    public function resolve($command)
    {
        $console = new \ReflectionClass($command);

        /** @var SymfonyCommand $command */
        $command = $console->newInstance($this->di);

        return $this->add($command);
    }

    /**
     * Get the command collection and add to parent class.
     *
     * @return Application
     */
    public function resolveCommands()
    {
        $this->bootstrap->registerAutoload();

        // we must register first the services before add events listener to DI.
        $this->bootstrap->registerService();

        $this->setupEventManager();
        $this->commands = $this->config['command'];

        foreach($this->commands as $command) {
            if(class_exists($command)) {
                $this->resolve($command);
            }
        }

        return $this;
    }

    private function setupEventManager()
    {
        // set new instance of events manager
        $eventManager = $this->bootstrap->getDI()->get('eventsManager');

        $this->bootstrap->setEventsManager($eventManager);

        $this->di->setEventsManager($eventManager);

        $this->bootstrap->registerEvents();

        return $this;
    }

    public function setBasePath($basePath)
    {
        $this->bootstrap->getDI()->setShared('basePath', function () use ($basePath) {

            return $basePath;
        });

        return $this;
    }

    public function getBootstrap()
    {
        return $this->bootstrap;
    }
}
