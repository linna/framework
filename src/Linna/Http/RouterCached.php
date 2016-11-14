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

use Linna\Http\Router;

/**
 * Extension of Router with caching system
 * Require memcached for run
 *
 */
class RouterCached extends Router
{
    /**
     * @var Object $cache Cache resource
     */
    private $cache;
    
    /**
     * Constructor
     *
     * @param array $routes List of registerd routes for the app in routes.php
     * @param array $options Options for router config
     * @param Memcached $memcached Memcached resource
     *
     * @todo Make router compatible with PSR7 REQUEST,instead of request uri pass a PSR7 request object
     */
    public function __construct(array $routes, array $options, \Memcached $memcached)
    {
        //call parent constructor
        parent::__construct($routes, $options);
        
        //set cache resource
        $this->cache = $memcached;
    }
    
    /**
     * Evaluate request uri
     *
     * @param string $requestUri Request uri
     * @param string $requestMethod Request method
     */
    public function validate(string $requestUri, string $requestMethod)
    {
        //check if route is already cached
        if (($cachedRoute = $this->cache->get($requestUri)) !== false) {
            //get cached route
            $this->route = $cachedRoute;
            //ecit
            return;
        }
        
        //if route not cached, validate it
        parent::validate($requestUri, $requestMethod);
        
        //cache validated route
        $this->cache->set($requestUri, $this->route);
    }
}
