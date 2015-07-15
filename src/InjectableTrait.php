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

/**
 * Trait InjectableTrait
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
trait InjectableTrait
{

    public $di;

    /**
     * Set DI
     *
     * @param \Phalcon\DiInterface $di
     * @return $this
     */
    public function setDI(\Phalcon\DiInterface $di)
    {
        $this->di = $di;

        return $this;
    }

    /**
     * Get DI.
     *
     * @param null|string $name
     * @return mixed
     */
    public function getDI($name = null)
    {
        return is_null($name) ? $this->di : $this->di->get($name);
    }

}