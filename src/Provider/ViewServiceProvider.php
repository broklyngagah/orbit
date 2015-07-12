<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\ServiceProvider;
use Orbit\Machine\Twig\Twig;
use Phalcon\Mvc\View as PhalconView;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

class ViewServiceProvider extends ServiceProvider
{

    protected $serviceName = 'view';

    public function register()
    {
        $config = $this->getConfig('view');
        
        $view = new PhalconView;

        // if no engine setted or default == 'null' then 
        // set no render level of view
        if('null' == $config->default) {
            $view::LEVEL_NO_RENDER;

            return $view;
        }

        $view->setViewsDir($config->path);
        $view->setRenderLevel(PhalconView::LEVEL_LAYOUT);

        $di = $this->getDI();

        $default = 'register'.ucfirst($config->default);

        $this->{$default}($view, $di, $config);

        return $view;
    }

    private function registerTwig(&$view, $di, $config)
    {
        $view->registerEngines([
            '.twig' => function ($view, $di) use($config) {

                $twigOptions = [
                    'cache' => $config->engine['twig']['cache'],
                    'debug' => $this->getConfig('app')->debug,
                    'auto_reload' => $this->getConfig('app')->debug,
                    'optimizations' => 0,
                ];

                $options = [
                    'is_safe' => ['html']
                ];

                $userFunc = [
                    new \Twig_SimpleFunction('session', function () use ($di) {
                        return $di->getShared('session');
                    }, $options),
                    new \Twig_SimpleFunction('content', function () use ($view) {
                        return $view->getContent();
                    }, $options),
                ];

                $twig = new Twig($view, $di, $twigOptions, $userFunc);

                return $twig;
            }
        ]);

        return;
    }


    private function registerVolt(&$view, $di, $config)
    {
        $view->registerEngines([
                ".volt" => function ($view, $di) use ($config) {

                    $volt = new VoltEngine($view, $di);

                    $options = [
                        'compileAlways' => $this->getConfig('app')->debug,
                        'compiledPath' => $config->engine->volt->cache,
                        'compiledSeparator' => '_',
                    ];

                    $volt->setOptions($options);

                    $compiler = $volt->getCompiler();

                    return $volt;
                }
            ]);

            return;
    }

}
