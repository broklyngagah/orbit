<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Phalcon\Mvc\Url;

class UrlServiceProvider extends ServiceProvider
{

    protected $serviceName = 'url';

    public function register()
    {
        $url = new Url;
        $url->setBaseUri($this->getConfig()->app->url);

        return $url;
    }
}
