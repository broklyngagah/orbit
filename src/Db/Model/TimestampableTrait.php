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

/**
 * Trait TimestampableTrait
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
trait TimestampableTrait
{

    /**
     * Create at date time.
     * @var string
     */
    public $created_at;

    /**
     * Update at date time.
     * @var string
     */
    public $updated_at;

    /**
     * Will be set the created at property with now();
     * @return string       Datetime format : 'Y-m-d H:i:s'
     */
    public function beforeCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
    }

    /**
     * Will be set the updated at property with now();
     * @return string       Datetime format : 'Y-m-d H:i:s'
     */
    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

}