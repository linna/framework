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

/**
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 */
class Router
{
    /**
     * @var array Config options for class
     */
    protected $options = [
        'basePath'    => '/',
        'badRoute'    => '',
        'rewriteMode' => false,
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
     *
     * @param array $routes  List of registerd routes for the app in routes.php
     * @param array $options Options for router config
     *
     * @todo Make router compatible with PSR7 REQUEST, instead of request uri pass a PSR7 request object
     */
    public function __construct(array $routes, array $options)
    {
        //set options
        $this->options = array_replace_recursive($this->options, $options);

        //set routes
        $this->routes = $routes;
    }

    /**
     * Evaluate request uri.
     *
     * @param string $requestUri    Request uri
     * @param string $requestMethod Request method
     *
     * @return bool
     */
    public function validate(string $requestUri, string $requestMethod) : bool
    {
        //get the current uri
        $currentUri = $this->getCurrentUri($requestUri);
      
        //matches set empty array
        $matches = [];

        //valid route set to 0
        $validRoute = 0;

        //filter registered routes for find route that match with current uri
        foreach ($this->routes as $value) {
            if (preg_match('`^'.preg_replace($this->matchTypes, $this->types, $value['url']).'/?$`', $currentUri, $matches)) {
                //$matches = $tempMatches;
                $validRoute = $value;
                break;
            }
        }

        //route daesn't macth
        if (!$validRoute) {
            //check and build for bad route
            $this->buildBadRoute();

            return false;
        }

        //non allowed method
        if (strpos($validRoute['method'], $requestMethod) === false) {
            //check and build for bad route
            $this->buildBadRoute();

            return false;
        }

        //add to route array the passed uri for param check when call
        $validRoute['matches'] = $matches;

        //route match and there is a subpattern with action
        if (count($matches) > 1) {
            //assume that subpattern rapresent action
            $validRoute['action'] = $matches[1];

            //url clean
            $validRoute['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $validRoute['url']);
        }

        //assign valid route
        $this->route = new Route(
            $validRoute['name'],
            $validRoute['method'],
            $validRoute['model'],
            $validRoute['view'],
            $validRoute['controller'],
            $validRoute['action'],
            $this->buildParam($validRoute)
        );

        return true;
    }

    /**
     * Check if a route is valid and
     * return the route object else return a bad route object.
     *
     * @return Route|bool
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Actions for bad route
     * 
     * @return bool
     */
    private function buildBadRoute() : bool
    {
        //check if there is a declared route for errors, if no exit with false
        if (($key = array_search($this->options['badRoute'], array_column($this->routes, 'name'))) === false){
            $this->route = false;
            return false;
        }
        
        //pick route for errors
        $route = $this->routes[$key];
        
        //build and store route for errors
        $this->route = new Route(
            $route['name'],
            $route['method'],
            $route['model'],
            $route['view'],
            $route['controller'],
            $route['action'],
            []
        );
        
        return true;
    }
    
    /**
     * Try to find param in a valid route.
     *
     * @param array $route Array with route caracteristics
     *
     * @return array Array with param passed from uri
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
     * @return string Uri from browser
     */
    private function getCurrentUri(string $passedUri): string
    {
        if ($this->options['rewriteMode'] === false) {
            $passedUri = str_replace('index.php?/', '', $passedUri);
        }

        $url = $passedUri ?? '/';
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return '/'.substr($url, strlen($this->options['basePath']));
    }
}
