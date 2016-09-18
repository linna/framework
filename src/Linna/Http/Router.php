<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Http;

use \Linna\classOptionsTrait;

/**
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 *
 */
class Router
{
    use classOptionsTrait;
    
    /**
     * Utilized with classOptionsTrait
     *
     * @var array Config options for class
     */
    private $options = array(
        'basePath' => '/',
        'badRoute' => '',
        'rewriteMode' => false
    );
    
    /**
     * @var object $route Utilized for return the most recently parsed route
     */
    private $route;

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
    private $currentUri = '';
    
    
    /**
     * Constructor
     *
     * @param array $routes List of registerd routes for the app in routes.php
     * @param array $options Options for router config
     *
     * @todo Make router compatible with PSR7 REQUEST,instead of request uri pass a PSR7 request object
     */
    public function __construct(array $routes, array $options)
    {
        //set options
        $this->options = $this->overrideOptions($this->options, $options);
        
        //set routes
        $this->routes = $routes;
    }
    
    /**
     * Evaluate request uri
     *
     * @param string $requestUri Request uri
     */
    public function validate(string $requestUri)
    {
        //get the current uri
        $this->currentUri = $this->getCurrentUri($requestUri);
        
        //try to get a route
        $this->route = $this->match($this->routes[array_search($this->options['badRoute'], array_column($this->routes, 'name'))]);
    }
    
    /**
     * Check if a route is valid and
     * return the route object else return a bad route object
     *
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * Build Route class
     *
     * @param array $route
     * @return \Linna\Http\Route
     */
    private function buildRoute(array $route)
    {
        //try to find param from route if route is not bad route
        $param = ($route['name'] !== $this->options['badRoute']) ? $this->buildParam($route) : array();
        
        //return new route object
        return new Route(
                $route['name'], $route['method'], $route['model'], $route['view'], $route['controller'], $route['action'], $param
        );
    }
    
    /**
     * Check if the requested uri is a valid route
     *
     * @param object $route Start with default route, bad route
     *
     * @return array Contains properties of route
     */
    private function match(array $route)
    {
        foreach ($this->routes as $value) {
            $regex = '`^'.preg_replace($this->matchTypes, $this->types, $value['url']).'/?$`';

            //check if route from browser match with registered routes
            $m = preg_match($regex, $this->currentUri, $matches);

            //match and there is a subpattern for a route with multiple actions
            if ($m === 1 && sizeof($matches) > 1) {

                //set $validRoute
                $route = $value;

                //add to route array the passed uri for param check when call
                $route['matches'] = $matches;

                //assume that subpattern rapresent action
                $route['action'] = $matches[1];

                //url clean
                $route['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $route['url']);

                break;
            }

            //match
            if ($m === 1) {
                //set valid route
                $route = $value;

                //add to route array the passed uri for param check when call
                $route['matches'] = $matches;
                
                break;
            }
        }
        
        return $this->buildRoute($route);
    }
    
    /**
     * Try to find param in a valid route
     *
     * @param array $route Array with route caracteristics
     *
     * @return array Array with param passed from uri
     */
    private function buildParam(array $route)
    {
        $param = array();
        
        $url = explode('/', $route['url']);
        $matches = explode('/', $route['matches'][0]);
        
        $rawParam = array_diff($matches, $url);

        foreach ($rawParam as $key => $value) {
            $paramName = preg_replace('`^(\[)|(\])$`', '', $url[$key]);

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
    private function getCurrentUri(string $passedUri)
    {
        if ($this->options['rewriteMode'] === false) {
            $passedUri = str_replace('/index.php?/', '', $passedUri);
        }
            
        $url = isset($passedUri) ? $passedUri : '/';
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return '/'.substr($url, strlen($this->options['basePath']));
    }
}
