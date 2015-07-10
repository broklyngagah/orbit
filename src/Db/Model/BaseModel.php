<?php

/*
 * This file is part of the Orbit Machine Package.
 *
 * (c) Pieter Lelaona <broklyn.gagah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Orbit\Machine\Db\Model;

use Phalcon\Mvc\Model as PhalconModel;

/**
 * BaseModel class.
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class BaseModel extends PhalconModel
{
    /**
     * Source of Model (table name).
     * @var string
     */
    protected $source;

    /**
     * Get source of model.
     * @return string   (table name)
     */
    public function getSource()
    {
        return $this->source;
    }
}
