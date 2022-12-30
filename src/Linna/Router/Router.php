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
 * <p>Manage routes, verify every resource requested by a client and return
 * a RouteInterface object.</p>
 */
class Router
{
    /** @var RouteInterface Utilized to return the most recently parsed route. */
    protected RouteInterface $route;

    /** @var array<string> List of regex to find parameter inside passed routes. */
    private array $matchTypes = [
        '`\[[0-9A-Za-z._-]+\]`',
    ];

    /** @var array<string> List of regex to find type of parameter inside passed routes. */
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
     * @param RouteCollection<Route> $routes                          The route collection used by the router as list of valid routes.
     * @param string                 $basePath                        The base path from which the router evaluate a route.
     * @param bool                   $rewriteMode                     Specify if the router have to work in rewrite mode.
     * @param string                 $rewriteModeFalseEntryPoint      The entry point for the router if rewrite mode is off.
     * @param bool                   $parseQueryStringRewriteModeTrue Tell the router if the query string of the uri should be parsed in rewrite mode.
     */
    public function __construct(
        /** @var RouteCollection<Route> The route collection used by the router as list of valid routes. */
        protected RouteCollection $routes,

        /** @var string The base path from which the router evaluate a route. */
        protected string $basePath = '/',

        /** @var bool Specify if the router have to work in rewrite mode. */
        protected bool $rewriteMode = false,

        /** @var string The entry point for the router if rewrite mode is off. */
        protected string $rewriteModeFalseEntryPoint = '/index.php?',

        /** @var bool Tell the router if the query string of the uri should be parsed in rewrite mode. */
        protected bool $parseQueryStringRewriteModeTrue = false
    ) {
    }

    /**
     * Evaluate request uri.
     *
     * @param string $requestUri    The request target in HTTP request start line.
     * @param string $requestMethod The HTTP method in HTTP request start line.
     *
     * @return bool True if the evaluated route is valid, false otherwise.
     */
    public function validate(string $requestUri, string $requestMethod): bool
    {
        // Route or NullRoute object
        $route = $this->findRoute($this->filterUri($requestUri), $requestMethod);

        // if Route
        if ($route instanceof Route) {
            $this->route = $this->buildRoute($route);
            return true;
        }

        // else Null route
        $this->route = $route;

        return false;
    }

    /**
     * Find if provided route match with one of registered routes.
     *
     * @param string $path   The request target in HTTP request start line.
     * @param string $method The HTTP method in HTTP request start line.
     *
     * @return RouteInterface A <code>Route</code> object if the route is valid, otherwise a
     *                        <code>NullRoute</code> object if the requested route doesn't exist.
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
     * @param Route $route The registered route which will be enriched with data from the request.
     *
     * @return RouteInterface A <code>Route</code> object containing the data from request.
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
            method:     $route->method,
            path:       $rPath,
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
     * @param string $path The request target in HTTP request start line, at
     *                     this point could contains parameters represented as
     *                     part of the path.
     *
     * @return array<mixed> An array containing possible parameters.
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
     * Return the result of the last route validation.
     *
     * @return RouteInterface <code>Route</code> object if the route was valid, NullRoute otherwise.
     */
    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * Analize current uri, sanitize and return it.
     *
     * @param string $passedUri The request target in HTTP request start line.
     *
     * @return string The sanitized uri.
     */
    private function filterUri(string $passedUri): string
    {
        //sanitize url
        $url = \filter_var($passedUri, FILTER_SANITIZE_URL);

        //check for rewrite mode and remove the entry point if present
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

            parse_str($queryString, $this->queryParam);
        }

        return (\substr($url, 0, 1) === '/') ? $url : '/'.$url;
    }

    /**
     * Map a route, add it to the routes collection used by the router.
     *
     * @param Route $route The route should be mapped.
     *
     * @return void
     */
    public function map(Route $route): void
    {
        $this->routes->append($route);
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
