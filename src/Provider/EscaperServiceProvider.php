<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Phalcon\Escaper;

class EscaperServiceProvider extends ServiceProvider
{

    protected $serviceName = 'escaper';

    public function register()
    {
        $escaper = new Escaper;
        $escaper->setEncoding('utf-8');

        return $escaper;
    }
}
