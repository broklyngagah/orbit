<?php

/*
 * This file is part of the Orbit Machine Package.
 *
 * (c) Pieter Lelaona <broklyn.gagah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Orbit\Machine\Config;

use ArrayAccess;
use Orbit\Machine\Support\Filesystem;
use Phalcon\Config as PhalconConfig;
use Orbit\Machine\Support\Arr;

/**
 * Config Class Container
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Config implements ArrayAccess, ConfigInterface
{

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Config array format.
     *
     * @var array $configs
     */
    protected $configs = [];

    public function __construct($compiled = null, $basePath)
    {
        $this->files = new Filesystem;

        $this->setBasePath($basePath);

        $this->setDirectory($basePath . '/resources/configs');

        $this->setupConfig($compiled);
    }

    public function getConfig($key = null)
    {
        return is_null($key) ? $this->configs : $this->get($key);
    }

    /**
     * Setup config to try to set the configs property.
     *
     * @param  string $compiledFile
     * @return array
     * @throws InvalidConfigTypeException
     */
    public function setupConfig($compiledFile = null)
    {
        if(!is_null($compiledFile) && $this->files->isFile($compiledFile)) {
            $this->configs = $this->files->getRequire($compiledFile);
        } else {
            $this->configs = $this->build();
        }

        if(!is_array($this->configs)) {
            throw new InvalidConfigTypeException("Compiled file must be an array.");
        }

        return $this;
    }

    /**
     * Set value of existing config key.
     *
     * @param mixed|string $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        Arr::set($this->configs, $key, $value);

        return $this;
    }

    public function get($key)
    {
        return Arr::get($this->configs, $key);
    }

    /**
     * @inheritdoc
     */
    public function add($key, $value)
    {
        Arr::add($this->configs, $key, $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return isset($this->configs['$key']);
    }

    /**
     * @inheritdoc
     */
    public function setDirectory($path)
    {
        $this['directory'] = $path;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDirectory()
    {
        return $this['directory'];
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        $files = $this->files->files($this->getDirectory());
        $configs = [];
        foreach($files as $file) {
            $configs[$this->files->name($file)] = $this->files->getRequire($file);
        }

        return $configs;
    }

    /**
     * @inheritdoc
     */
    public function getPhalconConfig()
    {
        return new PhalconConfig($this->arrConfig);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if(is_null($offset)) {
            $this->configs[] = $value;
        } else {
            $this->configs[$offset] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->configs[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->configs[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return isset($this->configs[$offset]) ? $this->configs[$offset] : null;
    }

    /**
     * @inheritdoc
     */
    public function getBasePath()
    {
        return $this->configs['base_path'];
    }

    /**
     * @inheritdoc
     */
    public function setBasePath($basePath)
    {
        return $this->set('base_path', $basePath);
    }
}
