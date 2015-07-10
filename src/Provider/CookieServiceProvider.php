<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Phalcon\Http\Response\Cookies;

class CookieServiceProvider extends ServiceProvider
{

    protected $serviceName = 'cookie';

    public function register()
    {
        $cookie = new Cookies;
        $cookie->useEncryption(true);

        return $cookie;
    }
}
