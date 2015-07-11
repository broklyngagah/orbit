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
use Orbit\Machine\Support\FileNotFoundException;
use Phalcon\Config as PhalconConfig;

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

    /**
     * Setup config to try to set the configs property.
     * 
     * @param  string $compiledFile
     * @return array
     */
    protected function setupConfig($compiledFile = null)
    {
        if(! is_null($compiledFile) && $this->files->isFile($compiledFile)) {
            $this->configs = $this->files->getRequire($compiledFile);
        } else {
        	$this->configs = $this->build();
        }

        return $this;
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
        foreach ($files as $file) {
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
        if (is_null($offset)) {
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
     * Gets the Base path of skeleton.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this['base_path'];
    }

    /**
     * Sets the Base path of skeleton.
     *
     * @param string $basePath the base path
     *
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this['base_path'] = $basePath;

        return $this;
    }
}
