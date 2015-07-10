<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;

class CryptServiceProvider extends ServiceProvider
{

    protected $serviceName = 'crypt';

    public function register()
    {
        $crypt = new \Orbit\Machine\Encryption\Encrypter($this->getConfig()->app->key);
        $crypt->setCipher($this->getConfig()->app->cipher);
        $crypt->setMode($this->getConfig()->app->cipher_mode);

        return $crypt;
    }
}