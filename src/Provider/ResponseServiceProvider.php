<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;

class ResponseServiceProvider extends ServiceProvider
{

    protected $serviceName = 'response';

    public function register()
    {
        $this->setCookie();

        return new Response;
    }

    private function setCookie()
    {
        $config = $this->getConfig('session');

        $this->getDI()->setShared('cookies', function () use ($config) {
            $cookies = new Cookies;
            $cookies->useEncryption($config->encrypt);

            return $cookies;
        });
    }
}
