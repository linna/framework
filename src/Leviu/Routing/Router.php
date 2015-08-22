<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\Routing;

/**
 * Router
 * - Class for manage routes, verify every resource requested by browser and return
 * a RouteInterface Object 
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
        '`\[int:[0-9A-Za-z]+\]`',
        '`\[string:[0-9A-Za-z]+\]`'
    );

    /**
     * @var array List of regex for find type of parameter inside passed routes
     */
    protected $types = array(
        '[0-9]++',
        '[0-9A-Za-z]++'
    );

    /**
     * @var string Request uri from browser (start from $_SERVER['REQUEST_URI'])
     */
    protected $currentUri = '';

    /**
     * Router constructor
     * 
     * @param array $routes list of registerd routes for the app in routes.php
     * @param string $basePath Directory of app from config.php
     * @since 0.1.0
     */
    public function __construct($routes, $basePath = '')
    {
        //set basePath
        $this->basePath = $basePath;

        //set routes
        $this->routes = $routes;

        //get the current uri
        $this->currentUri = $this->getCurrentUri();

        //try to get a route
        $this->route = $this->match();
    }

    /**
     * getRoute
     * 
     * This method check if a route is valid and 
     * return the route object else return a bad route object
     * 
     * @return \App_mk0\BadRoute|\App_mk0\Route
     * @since 0.1.0
     */
    public function getRoute()
    {

        //check if current route is valide
        switch ($this->route) {
            case null:
                //return new bad route object
                return new Route(null, null, null, null, null);
            default:
                //try to find param from route
                $param = $this->buildParam($this->route);
                //return new route object
                return new Route(
                        $this->route['name'],
                        $this->route['method'],
                        $this->route['controller'],
                        $this->route['action'],
                        $param
                );
        }
    }

    /**
     * match
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
            $c = "`^{$c}$`";
            
            //check if route from browser match with registered routes
            $m = preg_match($c, $this->currentUri, $matches);

            //if match
            if ($m === 1) {
                
                //set $validRoute
                $validRoute = $value;
                
                //add to route array the passed uri for param check when call
                //$this->buildParam($this->route);
                $validRoute['matches'] = $matches[0];
                break;
            }
        }

        //return route
        return $validRoute;
    }

    /**
     * buildPAram
     * 
     * Try to find param in a valid route
     * 
     * @param array $validRoute Array with route caracteristics
     * @return array Array with param passed from uri
     * @since 0.1.0
     */
    protected function buildParam($validRoute)
    {
        $url = explode('/', $validRoute['url']);
        $matches = explode('/', $validRoute['matches']);

        //var_dump($url);
        //var_dump($matches);

        $c = 1;
        $j = sizeof($url);
        $param = array();

        while ($c < $j) {
            if ($url[$c] !== $matches[$c]) {
                $keyType = preg_replace('`^(\[)|(\])$`', '', $url[$c]);
                $keyType = explode(':', $keyType);

                $type = $keyType[0];
                $key = $keyType[1];
                $value = $matches[$c];

                /**
                 * @todo capire se il controllo dei tipi lo facciamo qui o lo facciamo altrove
                 */
                switch ($type) {
                    case 'int':
                        $param[$key] = (int) $value;
                        break;
                    case 'string':
                        $param[$key] = (string) $value;
                        break;
                }
            }

            $c++;
        }
        
        return $param;
    }

    /**
     * getCurrentUri
     * 
     * Analize $_SERVER['REQUEST_URI'] for current uri, sanitize and return it
     * 
     * @return string Uri from browser
     * @since 0.1.0
     */
    protected function getCurrentUri()
    {
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return '/' . substr($url, strlen($this->basePath));
    }
}
