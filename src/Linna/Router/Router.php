<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

use BadMethodCallException;

/**
 * Router.
 *
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 */
class Router
{
    /**
     * @var string Base path for route evaluation.
     */
    protected $basePath = '/';

    /**
     * @var bool|string Fallback route name for 404 errors.
     */
    protected $badRoute = false;

    /**
     * @var bool Use of url rewriting.
     */
    protected $rewriteMode = false;

    /**
     * @var string Router access point without rewrite engine.
     */
    protected $rewriteModeOffRouter = '/index.php?';

    /**
     * @var RouteInterface Utilized for return the most recently parsed route
     */
    protected $route;

    /**
     * @var array Passed from constructor, is the list of registerd routes for the app
     */
    private $routes = [];

    /**
     * @var array List of regex for find parameter inside passed routes
     */
    private $matchTypes = [
        '`\[[0-9A-Za-z]+\]`',
    ];

    /**
     * @var array List of regex for find type of parameter inside passed routes
     */
    private $types = [
        '[0-9A-Za-z]++',
    ];

    /**
     * @var array preg match result for route.
     */
    private $routeMatches = [];

    /**
     * Constructor.
     * Accept as parameter a list routes and options.
     *
     * @param array $routes
     * @param array $options
     */
    public function __construct(array $routes, array $options = [])
    {
        [
            'basePath'             => $this->basePath,
            'badRoute'             => $this->badRoute,
            'rewriteMode'          => $this->rewriteMode,
            'rewriteModeOffRouter' => $this->rewriteModeOffRouter
        ] = array_replace_recursive([
            'basePath'             => '/',
            'badRoute'             => false,
            'rewriteMode'          => false,
            'rewriteModeOffRouter' => '/index.php?'
        ], $options);

        //set routes
        $this->routes = $routes;
    }

    /**
     * Evaluate request uri.
     *
     * @param string $requestUri
     * @param string $requestMethod
     *
     * @return bool
     */
    public function validate(string $requestUri, string $requestMethod): bool
    {
        $route = $this->findRoute($this->filterUri($requestUri), $requestMethod);

        if ($route instanceof Route) {
            $this->buildValidRoute($route);
            return true;
        }

        $this->buildErrorRoute();

        return false;
    }

    /**
     * Find if provided route match with one of registered routes.
     *
     * @param string $uri
     * @param string $method
     *
     * @return RouteInterface
     */
    private function findRoute(string $uri, string $method): RouteInterface
    {
        $matches = [];
        $route = new NullRoute();

        foreach ($this->routes as $value) {
            $urlMatch = preg_match('`^'.preg_replace($this->matchTypes, $this->types, $value->url).'/?$`', $uri, $matches);
            $methodMatch = strpos($value->method, $method);

            if ($urlMatch && $methodMatch !== false) {
                $route = clone $value;
                $this->routeMatches = $matches;
                break;
            }
        }

        return $route;
    }

    /**
     * Build a valid route.
     *
     * @param Route $route
     *
     * @return void
     */
    private function buildValidRoute(Route $route): void
    {
        //add to route array the passed uri for param check when call
        $matches = $this->routeMatches;

        //route match and there is a subpattern with action
        if (count($matches) > 1) {
            //assume that subpattern rapresent action
            $route->action = $matches[1];

            //url clean
            $route->url = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $route->url);
        }

        $route->param = $this->buildParam($route);

        $this->route = $route;
    }

    /**
     * Try to find parameters in a valid route and return it.
     *
     * @param Route $route
     *
     * @return array
     */
    private function buildParam(Route $route): array
    {
        $param = [];

        $url = explode('/', $route->url);
        $matches = explode('/', $this->routeMatches[0]);

        $rawParam = array_diff($matches, $url);

        foreach ($rawParam as $key => $value) {
            $paramName = strtr($url[$key], ['[' => '', ']' => '']);
            $param[$paramName] = $value;
        }

        return $param;
    }

    /**
     * Actions for error route.
     *
     * @return void
     */
    private function buildErrorRoute(): void
    {
        //check if there is a declared route for errors, if no exit with false
        if (($key = array_search($this->badRoute, array_column($this->routes, 'name'), true)) === false) {
            $this->route = new NullRoute();

            return;
        }

        //build and store route for errors
        $this->route = $this->routes[$key];
    }

    /**
     * Check if a route is valid and
     * return the route object else return a bad route object.
     *
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * Analize $_SERVER['REQUEST_URI'] for current uri, sanitize and return it.
     *
     * @param string $passedUri
     *
     * @return string
     */
    private function filterUri(string $passedUri): string
    {
        //sanitize url
        $url = filter_var($passedUri, FILTER_SANITIZE_URL);

        //check for rewrite mode
        $url = str_replace($this->rewriteModeOffRouter, '', $url);

        //remove basepath
        $url = substr($url, strlen($this->basePath));

        //remove doubled slash
        $url = str_replace('//', '/', $url);

        return (substr($url, 0, 1) === '/') ? $url : '/'.$url;
    }

    /**
     * Map a route.
     *
     * @param RouteInterface $route
     *
     * @return void
     */
    public function map(RouteInterface $route): void
    {
        array_push($this->routes, $route);
    }

    /**
     * Fast route mapping.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return void
     *
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        $method = strtoupper($name);

        if (in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->map(
                new Route(array_merge($arguments[2] ?? [], [
                    'method'   => $method,
                    'url'      => $arguments[0],
                    'callback' => $arguments[1]
                ]))
            );

            return;
        }

        throw new BadMethodCallException("Router->{$name}() method do not exist.");
    }
}
