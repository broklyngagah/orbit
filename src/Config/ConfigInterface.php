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
}