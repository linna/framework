<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Http;

use Linna\Shared\ClassOptionsTrait;

/**
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 */
class Router
{
    use ClassOptionsTrait;
    use FastMapTrait;

    /**
     * @var array Config options for class
     */
    protected $options = [
        'basePath'    => '/',
        'badRoute'    => false,
        'rewriteMode' => false,
        'router'      => 'index.php',
    ];

    /**
     * @var object|bool Utilized for return the most recently parsed route
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
     *
     * @todo Make router compatible with PSR7 REQUEST, instead of request uri pass a PSR7 request object
     */
    public function __construct(array $routes = [], array $options = [])
    {
        //set options
        $this->options = array_replace_recursive($this->options, $options);

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
    public function validate(string $requestUri, string $requestMethod) : bool
    {
        $currentUri = $this->getCurrentUri($requestUri);
        $matches = [];
        $route = [];

        //filter registered routes for find route that match with current uri
        foreach ($this->routes as $value) {
            if (preg_match('`^'.preg_replace($this->matchTypes, $this->types, $value['url']).'/?$`', $currentUri, $matches)) {
                $route = $value;
                break;
            }
        }

        if ($this->doesRouteNotFound($route, $requestMethod)){
            return false;
        }    
            
        $this->buildValidRoute($route, $matches);
        
        return true;
    }
    
    /**
     * Checks for a bad route.
     * 
     * @param array $route
     * @return bool
     */
    private function doesRouteNotFound(array $route, string $requestMethod) : bool
    {
        //route daesn't match
        if (sizeof($route) === 0) {
            $this->buildBadRoute();

            return true;
        }

        //non allowed method
        if (strpos($route['method'], $requestMethod) === false) {
            $this->buildBadRoute();

            return true;
        }
        
        return false;
    }
    
    /**
     * Build a valid route.
     * 
     * @param array $route
     * @param array $matches
     */
    private function buildValidRoute(array $route, array $matches)
    {
        //add to route array the passed uri for param check when call
        $route['matches'] = $matches;

        //route match and there is a subpattern with action
        if (count($matches) > 1) {
            //assume that subpattern rapresent action
            $route['action'] = $matches[1];

            //url clean
            $route['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $route['url']);
        }

        $route['param'] = $this->buildParam($route);

        $this->route = new Route($route);
    }


    /**
     * Check if a route is valid and
     * return the route object else return a bad route object.
     *
     * @return RouteInterface
     */
    public function getRoute() : RouteInterface
    {
        return $this->route;
    }

    /**
     * Actions for bad route.
     *
     * @return bool
     */
    private function buildBadRoute() : bool
    {
        //check if there is a declared route for errors, if no exit with false
        if (($key = array_search($this->options['badRoute'], array_column($this->routes, 'name'), true)) === false) {
            $this->route = new NullRoute();

            return false;
        }

        //pick route for errors
        $route = $this->routes[$key];

        //build and store route for errors
        $this->route = new Route($route);

        return true;
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
     * Analize $_SERVER['REQUEST_URI'] for current uri, sanitize and return it.
     *
     * @param string $passedUri
     *
     * @return string
     */
    private function getCurrentUri(string $passedUri): string
    {
        //sanitize url
        $url = filter_var($passedUri, FILTER_SANITIZE_URL);

        //remove basepath
        $url = substr($url, strlen($this->options['basePath']));

        //check for rewrite mode
        if (!$this->options['rewriteMode']) {
            $url = str_replace($this->options['router'], '', $url);

            return ($url === '') ? '/' : $url;
        }

        return '/'.$url;
    }

    /**
     * Map a route.
     *
     * @param array $route
     */
    public function map(array $route)
    {
        array_push($this->routes, $route);
    }
}
