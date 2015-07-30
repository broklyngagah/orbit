<?php

namespace Orbit\Machine\Provider;

use Orbit\Machine\Http\Router as OrbitRouter;
use Orbit\Machine\ServiceProvider;
use Orbit\Machine\Support\Filesystem;
use Phalcon\Mvc\Router\Annotations as RouteAnnotation;

class RouterServiceProvider extends ServiceProvider
{

    protected $serviceName = 'router';

    public function register()
    {
        /*require base_path('resources/configs/router.php');

        if($router instanceof RouteAnnotation) {
            return $this->loadAnnotationRouter($router);
        }

        return $router;*/

        $route = new OrbitRouter($this->getConfig('router'), false);
        $route->build();

        return $route;
    }

    private function loadAnnotationRouter(RouteAnnotation $router)
    {
        $path = base_path('app/Controller');

        $files = new Filesystem;

        $controllers = $files->files($path);

        foreach($controllers as $ctrl) {

            preg_match('/app\/Controller\/(.*)(Controller)\.php/', $ctrl, $match);

            if($match) {
                $resouce = "App\\Controller\\" . $match[1];
                $router->addResource($resouce);
            }
        }
    }
}
