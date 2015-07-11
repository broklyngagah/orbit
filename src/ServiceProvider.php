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

use Orbit\Machine\InjectableTrait;
use Phalcon\Config as PhalconConfig;

/**
 * Abstract class ServiceProvider
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
abstract class ServiceProvider
{
    /**
     * Initialize the Phalcon Dependency Injection on this class.
     */
    use InjectableTrait;

    /**
     * List of services class.
     * @var array $services
     */
    protected $services = [];

    /**
     * Service name.
     * @var string $serviceName
     */
    protected $serviceName;

    /**
     * Application configuration.
     * @var Config $config
     */
    protected $config;

    /**
     * Is shared service.
     * @var bool $shared
     */
    protected $shared = true;

    public function __construct($di, $config)
    {
        $this->setDI($di);
        $this->setConfig($config);
    }

    abstract public function register();

    /**
     * Gets the value of serviceName.
     *
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Sets the value of serviceName.
     *
     * @param mixed $serviceName the service name
     *
     * @return self
     */
    protected function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Gets the value of config.
     *
     * @param null $name
     * @return mixed
     */
    public function getConfig($name = null)
    {
        return $name ? (object)$this->config[$name] : (object)$this->config;
    }

    /**
     * Sets the value of config.
     *
     * @param \Orbit\Machine\Config\Config|PhalconConfig $config the config
     * @return \Orbit\Machine\ServiceProvider
     */
    public function setConfig($config)
    {
        if($config instanceof  \Orbit\Machine\Config\Config) {
            $this->config = $config;
        } else {
            $this->config = $config->getPhalconConfig();
        }

        return $this;
    }

    /**
     * Get service shared property.
     * @return bool
     */
    public function getShared()
    {
        return $this->shared;
    }

}
