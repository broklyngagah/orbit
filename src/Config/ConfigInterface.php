<?php

namespace Orbit\Machine\Config;

use Phalcon\Config as PhalconConfig;

/**
 * Config container contract
 */
interface ConfigInterface
{
	/**
     * Set config path directory.
     *
     * @param string $path
     * @return $this
     */
    public function setDirectory($path);

    /**
     * Convert and get configs container to phalcon config instance.
     *
     * @return string
     */
    public function getDirectory();

    /**
     * Get Phalcon config type.
     *
     * @return PhalconConfig
     */
    public function getPhalconConfig();

    /**
     * Build all configs files in 1 array.
     *
     * @return array
     */
    public function build();

    /**
     * Get a value of collection.
     *
     * @param   string $key
     * @return  array
     */
    public function get($key);

    /**
     * Set or replace existing key of array collection
     *
     * @param string|mixed $key
     * @param self
     */
    public function set($key, $value);

    /**
     * Add new key and value to existing collection
     *
     * @param $key
     * @param $value
     * @return  self
     */
    public function add($key, $value);

    /**
     * Check is the key is setted in collection.
     *
     * @param  string|mixed  $key
     * @return boolean
     */
    public function has($key);
}