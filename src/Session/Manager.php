<?php

namespace Orbit\Machine\Session;

use Closure;

class Manager
{
    /**
     * Connections list
     * @var array
     */
    protected $connections = [];

    /**
     * Connectors list
     * @var array
     */
    protected $connectors = [];

    /**
     * Session configuration
     * @var \Phalcon\Config
     */
    protected $config;

    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    public function connection($name = null)
    {
        $name = ! null == $name ?: $this->getDefaultDriver();

        // if no one connection has not been resolved yet, we will resolve it now.
        if(! isset($this->connections[$name])) {
            $this->connections[$name] = $this->resolve($name);
        }

        return $this->connections[$name];
    }

    public function addConnector($driver, Closure $resolver)
    {
        $this->connectors[$driver] = $resolver;
    }

    protected function resolve($name)
    {
        $config = $this->getConfig();

        return $this->getConnector($name)->connect($config);
    }

    /**
     * Get connector.
     *
     * @param  string                       $driver
     * @thrown InvalidArgumentException
     * @return [type]
     */
    protected function getConnector($driver)
    {
        if(isset($this->connectors[$driver])) {
            return call_user_func($this->connectors[$driver]);
        }

        throw new \InvalidArgumentException("it's no driver with name $driver", 1);
    }

    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * Get session config.
     * @return array
     */
    protected function getSessionConfig($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->config['session'][$name];
    }

    /**
     * Get default driver.
     *
     * @return array
     */
    protected function getDefaultDriver()
    {
        return $this->config['session']['default'];
    }

    /**
     * Get name of connection.
     *
     * @param  string $connection
     * @return string
     */
    public function getName($connection = null)
    {
        return $connection ?: $this->getDefaultDriver();
    }
}