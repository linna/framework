<?php

/**
 * Leviu.
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
namespace Leviu\Http;

/**
 * Router
 * - Class for manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Router
{
    /**
     * @var object Utilized for return the most recently parsed route
     */
    protected $route = null;

    /**
     * @var array Passed from constructor, is the list of registerd routes for the app
     */
    protected $routes = null;

    /**
     * @var string Passed from constructor, indicates directory of app, check config.php
     */
    protected $basePath = '';

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
     *
     * @var string Route for error page :) 
     */
    protected $badRoute = '';
    
    
    /**
     * Constructor.
     * 
     * @param array  $routes   list of registerd routes for the app in routes.php
     * @param object $options  mixed options for router
     *
     * @since 0.1.0
     */
    public function __construct($routes, $options)//$basePath = '')
    {
        //set basePath
        $this->basePath = $options->base_path;
        
        //set badRoute
        $this->badRoute = $options->bad_route;
        
        //set routes
        $this->routes = $routes;

        //get the current uri
        $this->currentUri = $this->getCurrentUri();

        //try to get a route
        $this->route = $this->match();
    }

    /**
     * getRoute.
     * 
     * This method check if a route is valid and 
     * return the route object else return a bad route object
     * 
     * @return \App_mk0\BadRoute|\App_mk0\Route
     *
     * @since 0.1.0
     */
    public function getRoute()
    {
        
        //check if current route is valide
        switch ($this->route) {
            case null:
                //return new bad route object
                
                //find declared error route
                $bad_route = $this->routes[array_search($this->badRoute, array_column($this->routes, 'name'))];
                
                //build param, no param, sure
                $param = $this->buildParam($this->route);
                
                //return page for bad route
                return new Route(
                        $bad_route['name'], $bad_route['method'], $bad_route['model'], $bad_route['view'], $bad_route['controller'], $bad_route['action'], $param
                );
                
            default:
                //try to find param from route
                $param = $this->buildParam($this->route);
                //return new route object
                return new Route(
                        $this->route['name'], $this->route['method'], $this->route['model'], $this->route['view'], $this->route['controller'], $this->route['action'], $param
                );
        }
    }

    /**
     * match.
     * 
     * This method check if the requested uri is a valid route
     *
     * if valid return an array like this
     * array (size=6)
     * 'name' => null
     * 'method' => string 'GET' (length=3)
     * 'url' => string '/' (length=1)
     * 'controller' => string 'Home' (length=4)
     * 'action' => null
     * 'matches' => string '/' (length=1)
     * 
     * @return array|null Array contains properties of route
     *
     * @since 0.1.0
     */
    protected function match()
    {

        //declare var
        $validRoute = null;

        foreach ($this->routes as $value) {

            // replace declared parameter in registered routes with regex
            $c = preg_replace($this->matchTypes, $this->types, $value['url']);
            // set regex delimiter
            $c = "`^{$c}/?$`";

            //debug
            //var_dump($c);

            //check if route from browser match with registered routes
            $m = preg_match($c, $this->currentUri, $matches);

            //debug
            //var_dump($matches);

            //debug
            //var_dump(sizeof($matches));

            //if match and there is a subpattern for a route with multiple actions
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

            //if match
            if ($m === 1) {
                //set $validRoute
                $validRoute = $value;

                //add to route array the passed uri for param check when call
                $validRoute['matches'] = $matches;
                break;
            }
        }

        //debug
        //var_dump($validRoute);

        //return route
        return $validRoute;
    }

    /**
     * buildParam.
     * 
     * Try to find param in a valid route
     * 
     * @param array $validRoute Array with route caracteristics
     *
     * @return array Array with param passed from uri
     *
     * @since 0.1.0
     */
    protected function buildParam($validRoute)
    {
        // var_dump($validRoute);
        // if (isset($validRoute['matches'][1]))
        // {
        //    $validRoute['url'] = preg_replace('`\([0-9A-Za-z\|]++\)`', $validRoute['matches'][1], $validRoute['url']);
        //  $matches = explode('/', $validRoute['matches'][1]);
        // }

        //debug
        //var_dump($validRoute['url']);

        $url = explode('/', $validRoute['url']);
        $matches = explode('/', $validRoute['matches'][0]);

        //debug
        //var_dump($url);
        //var_dump($matches);

        //old code
        //$c = 1;
        //$j = sizeof($url);

        $param = array();
        $rawParam = array_diff($matches, $url);

        //debug
        //var_dump(array_diff($matches,$url));

        foreach ($rawParam as $key => $value) {
            $paramName = preg_replace('`^(\[)|(\])$`', '', $url[$key]);

            $param[$paramName] = $value;
        }

        //previsous code :)
        /*
        while ($c < $j) {
            if ($url[$c] !== $matches[$c]) {
                $key = preg_replace('`^(\[)|(\])$`', '', $url[$c]);
                $keyType = explode(':', $keyType);

                $type = $keyType[0];
                $key = $keyType;
                $value = $matches[$c];

                switch ($type) {
                    case 'int':
                        $param[$key] = $value;
                        break;
                    case 'string':
                        $param[$key] = (string) $value;
                        break;
                }
            }

            $c++;
        }
         */

        //debug   
        //var_dump($param);

        return $param;
    }

    /**
     * getCurrentUri.
     * 
     * Analize $_SERVER['REQUEST_URI'] for current uri, sanitize and return it
     * 
     * @return string Uri from browser
     *
     * @since 0.1.0
     */
    protected function getCurrentUri()
    {
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return '/'.substr($url, strlen($this->basePath));
    }
}
