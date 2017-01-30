<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Http;

/**
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 *
 */
class Router
{
    /**
     * Utilized with classOptionsTrait
     *
     * @var array Config options for class
     */
    protected $options = array(
        'basePath' => '/',
        'badRoute' => '',
        'rewriteMode' => false
    );
    
    /**
     * @var object $route Utilized for return the most recently parsed route
     */
    protected $route;

    /**
     * @var array $routes Passed from constructor, is the list of registerd routes for the app
     */
    private $routes;

    /**
     * @var array $matchTypes List of regex for find parameter inside passed routes
     */
    private $matchTypes = array(
        '`\[[0-9A-Za-z]+\]`',
    );

    /**
     * @var array $types List of regex for find type of parameter inside passed routes
     */
    private $types = array(

        '[0-9A-Za-z]++',
    );

    /**
     * @var string $currentUri Request uri from browser (start from['REQUEST_URI'])
     */
    protected $currentUri = '';
    
    
    /**
     * Constructor
     *
     * @param array $routes List of registerd routes for the app in routes.php
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
     * Evaluate request uri
     *
     * @param string $requestUri Request uri
     * @param string $requestMethod Request method
     */
    public function validate(string $requestUri, string $requestMethod) : bool
    {
        //get the current uri
        $this->currentUri = $this->getCurrentUri($requestUri);
        
        //find bad route
        $route = $this->routes[array_search($this->options['badRoute'], array_column($this->routes, 'name'))];
        
        //matches set empty array
        $matches = [];
        //valid route set to 0
        $validRoute = 0;
        
        //filter registered routes for find route that match with current uri
        foreach ($this->routes as $value) {
            if (preg_match('`^'.preg_replace($this->matchTypes, $this->types, $value['url']).'/?$`', $this->currentUri, $tempMatches)) {
                $matches = $tempMatches;
                $validRoute = $value;
                break;
            }
        }
        
        //route daesn't macth
        if (!$validRoute) {
            //assign error route
            $this->route = $this->buildRoute($route);
            return false;
        }
        
        //non allowed method
        if (strpos($validRoute['method'], $requestMethod) === false) {
            //assign error route
            $this->route = $this->buildRoute($route);
            return false;
        }
        
        //route match and there is a subpattern with action
        if (sizeof($matches) > 1) {
            //add to route array the passed uri for param check when call
            $validRoute['matches'] = $matches;
            
            //assume that subpattern rapresent action
            $validRoute['action'] = $matches[1];

            //url clean
            $validRoute['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $validRoute['url']);
            
            //assign valid route
            $this->route = $this->buildRoute($validRoute);
            return false;
        }
        
        //add to route array the passed uri for param check when call
        $validRoute['matches'] = $matches;

        //assign valid route
        $this->route = $this->buildRoute($validRoute);

        return true;
    }
    
    /**
     * Check if a route is valid and
     * return the route object else return a bad route object
     *
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }
    
    /**
     * Build Route class
     *
     * @param array $route
     * @return \Linna\Http\Route
     */
    private function buildRoute(array $route): Route
    {
        //try to find param from route if route is not bad route
        $param = ($route['name'] !== $this->options['badRoute']) ? $this->buildParam($route) : array();
        
        //return new route object
        return new Route(
            $route['name'],
            $route['method'],
            $route['model'],
            $route['view'],
            $route['controller'],
            $route['action'],
            $param
        );
    }
    
    /**
     * Try to find param in a valid route
     *
     * @param array $route Array with route caracteristics
     *
     * @return array Array with param passed from uri
     */
    private function buildParam(array $route): array
    {
        $param = array();
        
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
     * Analize $_SERVER['REQUEST_URI'] for current uri, sanitize and return it
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
