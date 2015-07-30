<?php

namespace Orbit\Machine\Http;

use InvalidArgumentException;
use Mockery\CountValidator\Exception;
use Phalcon\Mvc\Router as PhalconRouter;

class Router extends PhalconRouter
{

    /**
     * Routes collection.
     *
     * @var array
     */
    protected $routes;

    /**
     * Default http method.
     * @var array
     */
    protected $defaultHttpMethod = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE',
    ];

    /**
     * Default action for resource route.
     * @var array
     */
    private $defaultResouceAction = [
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ];

    protected $extractedRoute = [];

    public function __construct(array $routes, $defaultRoute = false)
    {
        parent::__construct(false);
        $this->setUriSource(static::URI_SOURCE_SERVER_REQUEST_URI);

        $this->routes = $routes;
    }

    public function build()
    {
        try {
            foreach($this->routes as $name => $route) {

                $route = $this->extractRoutes($route);

                if(in_array($route['method'], $this->defaultHttpMethod)) {
                    $httpMethod = "add{$route['method']}";

                    $path = [
                        'controller' => $route['controller'],
                        'action' => $route['action'],
                    ];

                    // setup route
                    $this->add($route['pattern'], $path)
                         ->via($route['method'])
                         ->setName($name);

                } else {
                    // for resource route.

                }
            }
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * Split the given string class / namespace of controller class and method. separted by '@'.
     *
     * @param array $routes
     * @return array
     * @throws InvalidArgumentException
     */
    protected function extractRoutes($routes)
    {
        $extract = preg_split('/\@/', $routes[2]);

        if(empty($extract)) {
            throw new InvalidArgumentException('Controller or Method notfound ' . ' [{$contrllerClass}]');
        }

        return $this->extractedRoute[] = [
            'method' => strtoupper($routes[0]),
            'pattern' => $routes[1],
            'controller' => $extract[0],
            'action' => $extract[1],
        ];
    }


}