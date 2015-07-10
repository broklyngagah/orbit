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

use Orbit\Machine\Support\Filesystem;

/**
 * Class Config
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Config
{

    /**
     * Config files directory.
     * @var string
     */
    protected $directory;

    /**
     * Config array format.
     * @var array
     */
    protected $arrConfig;

    /**
     * Set config path directory.
     * @param string $path
     * @return $this
     */
    public function setDirectory($path)
    {
        $this->directory = $path;

        return $this;
    }

    /**
     * Get config path directory.
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Build all configs files in 1 array.
     * @param null $compiledFile
     * @return $this
     * @throws \Orbit\Machine\Support\FileNotFoundException
     */
    public function build($compiledFile = null)
    {
        $reader = new Filesystem;

        if(! is_null($compiledFile) && $reader->isFile($compiledFile)) {
            $this->arrConfig = $reader->getRequire($compiledFile);

            return $this;
        }

        $files = $reader->files($this->getDirectory());
        $configs = [];
        foreach ($files as $file) {
            $configs[$reader->name($file)] = $reader->getRequire($file);
        }

        $this->arrConfig = $configs;

        return $this;
    }

    /**
     * Dump array config as Phalcon config
     * @return \Phalcon\Config
     */
    public function dump()
    {
        return new \Phalcon\Config($this->arrConfig);
    }

    /**
     * Gets the value of arrConfig.
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->arrConfig;
    }
}
