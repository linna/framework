<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Http;

/**
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 * 
 */
class Router
{
    use \Leviu\classOptionsTrait;
    
    /**
     * Utilized with classOptionsTrait
     * @var array Config options for class
     */
    protected $options = array(
        'basePath' => '/',
        'badRoute' => ''
    );
    
    /**
     * @var array Utilized for return the most recently parsed route
     */
    protected $route;

    /**
     * @var array Passed from constructor, is the list of registerd routes for the app
     */
    protected $routes = null;

    /**
     * @var array List of regex for find parameter inside passed routes
     */
    protected $matchTypes = array(
        '`\[[0-9A-Za-z]+\]`',
    );

    /**
     * @var array List of regex for find type of parameter inside passed routes
     */
    protected $types = array(

        '[0-9A-Za-z]++',
    );

    /**
     * @var string Request uri from browser (start from['REQUEST_URI'])
     */
    protected $currentUri = '';
    
    
    /**
     * Constructor.
     * 
     * @param string $requestUri Request uri
     * @param array $routes List of registerd routes for the app in routes.php
     * @param array $options Options for router config
     *
     * @todo Make router compatible with PSR7 REQUEST,instead of request uri pass a PSR7 request object
     * 
     */
    public function __construct($requestUri, $routes, $options)
    {
        //set options
        $this->options = $this->overrideOptions($this->options, $options);
        
        //set routes
        $this->routes = $routes;

        //get the current uri
        $this->currentUri = $this->getCurrentUri($requestUri);

        //try to get a route
        $this->route = $this->match();
    }

    /**
     * Check if a route is valid and 
     * return the route object else return a bad route object
     * 
     * @return Route
     *
     */
    public function getRoute()
    {
        //try to find param from route if route is not bad route
        $param = ($this->route['name'] !== $this->options['badRoute']) ? $this->buildParam($this->route) : array();
        //return new route object
        return new Route(
                $this->route['name'], $this->route['method'], $this->route['model'], $this->route['view'], $this->route['controller'], $this->route['action'], $param
        );
    }

    /**
     * Check if the requested uri is a valid route
     *
     * if valid return an array like this with route, if no return route for error page
     * array (size=6)
     * 'name' => null
     * 'method' => string 'GET' (length=3)
     * 'url' => string '/' (length=1)
     * 'controller' => string 'Home' (length=4)
     * 'action' => null
     * 'matches' => string '/' (length=1)
     * 
     * @return array Contains properties of route
     *
     */
    protected function match()
    {
        //declare var with bad route data
        $validRoute = $this->routes[array_search($this->options['badRoute'], array_column($this->routes, 'name'))];

        foreach ($this->routes as $value) {

            // replace declared parameter in registered routes with regex
            $c = preg_replace($this->matchTypes, $this->types, $value['url']);
            // set regex delimiter
            $c = "`^{$c}/?$`";

            //check if route from browser match with registered routes
            $m = preg_match($c, $this->currentUri, $matches);

            //match and there is a subpattern for a route with multiple actions
            if ($m === 1 && sizeof($matches) > 1) {

                //set $validRoute
                $validRoute = $value;

                //add to route array the passed uri for param check when call
                $validRoute['matches'] = $matches;

                //assume that subpattern rapresent action
                $validRoute['action'] = $matches[1];

                //url clean
                $validRoute['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $validRoute['url']);

                break;
            }

            //match
            if ($m === 1) {
                //set valid route
                $validRoute = $value;

                //add to route array the passed uri for param check when call
                $validRoute['matches'] = $matches;
                
                break;
            }
        }

        return (array) $validRoute;
    }

    /**
     * Try to find param in a valid route
     * 
     * @param array $validRoute Array with route caracteristics
     *
     * @return array Array with param passed from uri
     *
     */
    protected function buildParam($validRoute)
    {
        $url = explode('/', $validRoute['url']);
        $matches = explode('/', $validRoute['matches'][0]);

        $param = array();
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
     * @return string Uri from browser
     *
     */
    protected function getCurrentUri($passedUri)
    {
        $url = isset($passedUri) ? $passedUri : '/';
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return '/'.substr($url, strlen($this->options['basePath']));
    }
}
