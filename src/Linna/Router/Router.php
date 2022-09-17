<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

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
    /** @var RouteInterface Utilized for return the most recently parsed route */
    protected RouteInterface $route;

    /** @var array<string> List of regex for find parameter inside passed routes */
    private array $matchTypes = [
        '`\[[0-9A-Za-z._-]+\]`',
    ];

    /** @var array<string> List of regex for find type of parameter inside passed routes */
    private array $types = [
        '[0-9A-Za-z._-]++',
    ];

    /** @var array<mixed> preg_match result for route. */
    private array $routeMatches = [];

    /** @var array<mixed> Array with parameters from query string. */
    private array $queryParam = [];

    /**
     * Class Constructor.
     *
     * @param RouteCollection<Route> $routes
     * @param string                 $basePath
     * @param bool                   $rewriteMode
     * @param string                 $rewriteModeFalseEntryPoint
     * @param bool                   $parseQueryStringRewriteModeTrue
     */
    public function __construct(
        protected RouteCollection $routes,
        protected string $basePath = '/',
        protected bool $rewriteMode = false,
        protected string $rewriteModeFalseEntryPoint = '/index.php?',
        protected bool $parseQueryStringRewriteModeTrue = false
    ) {
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
            $this->route = $this->buildRoute($route);
            return true;
        }

        $this->route = $route;

        return false;
    }

    /**
     * Find if provided route match with one of registered routes.
     *
     * @param string $path
     * @param string $method
     *
     * @return RouteInterface
     */
    private function findRoute(string $path, string $method): RouteInterface
    {
        $matches = [];
        $route = new NullRoute();

        foreach ($this->routes as $value) {
            $pathMatch = \preg_match('`^'.\preg_replace($this->matchTypes, $this->types, $value->path).'/?$`', $path, $matches);
            $methodMatch = \strpos($value->method, $method);

            if ($pathMatch && $methodMatch !== false) {
                $route = clone $value;
                $this->routeMatches = $matches;
                break;
            }
        }

        return $route;
    }

    /**
     * Build a valid route instance starting from registered route.
     *
     * @param Route $route
     *
     * @return RouteInterface
     */
    private function buildRoute(Route $route): RouteInterface
    {
        //add to route array the passed uri for param check when call
        $matches = $this->routeMatches;

        $rAction = $route->action;
        $rPath = $route->path;

        //route match and there is a subpattern with action
        if (\count($matches) > 1) {
            //assume that subpattern rapresent action
            $rAction = $matches[1];

            //url clean
            $rPath = \preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $route->path);
        }

        return new Route(
            $route->method,
            $rPath,
            callback:   $route->callback,
            model:      $route->model,
            view:       $route->view,
            controller: $route->controller,
            name:       $route->name,
            action:     $rAction,
            default:    $route->default,
            param:      $this->buildParam($rPath),
            allowedIps: $route->allowedIps
        );
    }

    /**
     * Try to find parameters in a valid route and return it.
     *
     * @param string $path
     *
     * @return array<mixed>
     */
    private function buildParam(string $path): array
    {
        $param = [];

        $explodedPath = \explode('/', $path);
        $matches = \explode('/', $this->routeMatches[0]);

        $rawParam = \array_diff($matches, $explodedPath);

        foreach ($rawParam as $key => $value) {
            $paramName = \strtr($explodedPath[$key], ['[' => '', ']' => '']);
            $param[$paramName] = $value;
        }

        if ($this->parseQueryStringRewriteModeTrue) {
            return $param + $this->queryParam;
        }

        return $param;
    }

    /**
     * Create an array from query string params.
     *
     * @param string $queryString
     *
     * @return void
     */
    private function buildParamFromQueryString(string $queryString): void
    {
        $param = \array_map(function ($array_value) {
            $tmp = \explode('=', $array_value);
            $array_value = [];
            $key = $tmp[0];
            $value = '';

            if (isset($tmp[1])) {
                $value = \urldecode($tmp[1]);
            }

            $array_value[$key] = $value;

            return $array_value;
        }, \explode('&', $queryString));

        $temp = [];

        foreach ($param as $value) {
            if (\is_array($value)) {
                $temp = \array_merge($temp, $value);
                continue;
            }

            $temp[] = $value;
        }

        $this->queryParam = $temp;
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
     * Analize current uri, sanitize and return it.
     *
     * @param string $passedUri
     *
     * @return string
     */
    private function filterUri(string $passedUri): string
    {
        //sanitize url
        $url = \filter_var($passedUri, FILTER_SANITIZE_URL);

        //check for rewrite mode
        $url = \str_replace($this->rewriteModeFalseEntryPoint, '', $url);

        //remove basepath, if present
        if (\strpos($url, $this->basePath) === 0) {
            $url = \substr($url, \strlen($this->basePath));
        }

        //remove doubled slash
        $url = \str_replace('//', '/', $url);

        //check for query string parameters
        if (\strpos($url, '?') !== false) {
            $queryString = \substr(\strstr($url, '?'), 1);
            $url = \strstr($url, '?', true);

            $this->buildParamFromQueryString($queryString);
        }

        return (\substr($url, 0, 1) === '/') ? $url : '/'.$url;
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
        $this->routes[] = $route;
    }

    /**
     * Fast route mapping.
     *
     * @param string       $name
     * @param array<mixed> $arguments
     *
     * @return void
     *
     * @throws BadMethodCallException
     */
    /*public function __call(string $name, array $arguments)
    {
        $method = strtoupper($name);

        if (\in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
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
    }*/
}
