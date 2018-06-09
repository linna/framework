<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Http;

use BadMethodCallException;
use Linna\Shared\ClassOptionsTrait;

/**
 * Router.
 *
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 */
class Router
{
    use ClassOptionsTrait;

    /**
     * @var array Config options for class
     */
    protected $options = [
        'basePath'             => '/',
        'badRoute'             => false,
        'rewriteMode'          => false,
        'rewriteModeOffRouter' => '/index.php?',
    ];

    /**
     * @var RouteInterface Utilized for return the most recently parsed route
     */
    protected $route;

    /**
     * @var array Passed from constructor, is the list of registerd routes for the app
     */
    private $routes;

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
     * Constructor.
     * Accept as parameter a list routes and options.
     *
     * @param array $routes
     * @param array $options
     */
    public function __construct(array $routes = [], array $options = [])
    {
        //set options
        $this->setOptions($options);

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

        if ($route) {
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
     * @return array
     */
    private function findRoute(string $uri, string $method): array
    {
        $matches = [];
        $route = [];

        foreach ($this->routes as $value) {
            $urlMatch = preg_match('`^'.preg_replace($this->matchTypes, $this->types, $value['url']).'/?$`', $uri, $matches);
            $methodMatch = strpos($value['method'], $method);

            if ($urlMatch && $methodMatch !== false) {
                $route = $value;
                $route['matches'] = $matches;
                break;
            }
        }

        return $route;
    }

    /**
     * Build a valid route.
     *
     * @param array $route
     */
    private function buildValidRoute(array $route): void
    {
        //add to route array the passed uri for param check when call
        $matches = $route['matches'];

        //route match and there is a subpattern with action
        if (count($matches) > 1) {
            //assume that subpattern rapresent action
            $route['action'] = $matches[1];

            //url clean
            $route['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $route['url']);
        }

        $route['param'] = $this->buildParam($route);

        //delete matches key because not required inside route object
        unset($route['matches']);

        $this->route = new Route($route);
    }

    /**
     * Try to find parameters in a valid route and return it.
     *
     * @param array $route
     *
     * @return array
     */
    private function buildParam(array $route): array
    {
        $param = [];

        $url = explode('/', $route['url']);
        $matches = explode('/', $route['matches'][0]);

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
        if (($key = array_search($this->options['badRoute'], array_column($this->routes, 'name'), true)) === false) {
            $this->route = new NullRoute();

            return;
        }

        //pick route for errors
        $route = $this->routes[$key];

        //build and store route for errors
        $this->route = new Route($route);
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
        $url = str_replace($this->options['rewriteModeOffRouter'], '', $url);

        //remove basepath
        $url = substr($url, strlen($this->options['basePath']));

        //remove doubled slash
        $url = str_replace('//', '/', $url);

        return (substr($url, 0, 1) === '/') ? $url : '/'.$url;
    }

    /**
     * @var array Allowed Http methods for fast route mapping.
     */
    private $fastMapMethods = [
        'GET' => true,
        'POST' => true,
        'PUT' => true,
        'PATCH' => true,
        'DELETE' => true,
    ];

    /**
     * Map a route.
     *
     * @param array $route
     */
    public function map(array $route): void
    {
        array_push($this->routes, $route);
    }

    /**
     * Fast route mapping.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return void
     *
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        $method = strtoupper($name);

        if (isset($this->fastMapMethods[$method])) {
            $this->map($this->createRouteArray($method, $arguments[0], $arguments[1], $arguments[2] ?? []));

            return;
        }

        throw new BadMethodCallException(__METHOD__.": Router->{$name}() method do not exist.");
    }

    /**
     * Create route array for previous methods.
     *
     * @param string   $method
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     *
     * @return array
     */
    private function createRouteArray(string $method, string $url, callable $callback, array $options): array
    {
        $routeArray = (new Route([
            'method'   => $method,
            'url'      => $url,
            'callback' => $callback,
        ]))->toArray();

        $route = array_replace_recursive($routeArray, $options);

        return $route;
    }
}
