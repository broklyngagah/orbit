<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Orbit\Machine\Support\Arr;
use Orbit\Machine\Tracy\DbBar;

class DbServiceProvider extends ServiceProvider
{

    protected $serviceName = 'db';

    public function register()
    {
        $dbConfig = $this->getConfig()->database->toArray();
        $default = $dbConfig['default'];

        $config = $dbConfig[$default];

        $adapter = '\Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];

        $conn = new $adapter(Arr::except($config, 'adapter'));

        return $conn;
    }
}
