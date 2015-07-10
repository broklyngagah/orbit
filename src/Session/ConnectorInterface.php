<?php

namespace Orbit\Machine\Session;

interface ConnectorInterface
{

    /**
     * Connect to session adapter
     * @param  array  $config
     * @return mixed
     */
    public function connect(array $config);
}