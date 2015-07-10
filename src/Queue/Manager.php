<?php

namespace Orbit\Machine\Queue;

use Phalcon\Config;

class Manager
{
    protected $connections = [];

    protected $connectors = [];

    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
}
