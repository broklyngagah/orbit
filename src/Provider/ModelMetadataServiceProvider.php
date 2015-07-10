<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;

class ModelMetadataServiceProvider extends ServiceProvider
{

    protected $serviceName = 'modelsMetadata';

    public function register()
    {
        return new \Phalcon\Mvc\Model\MetaData\Memory();
    }
}






