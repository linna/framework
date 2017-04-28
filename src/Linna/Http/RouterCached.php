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

use Psr\SimpleCache\CacheInterface;

/**
 * ***IMPORTANT***
 * Using disk cache (best if with ram disk), this class is faster than router :D
 * memcached replaced by PSR-16 simple cache.
 *
 * ***OLD***
 * After some tests, this class has proved more slow than Router class because
 * get a value from memcached are expensive, more expensive than only check a route with
 * validate function.
 * This class remains a programming excercice :'(.
 *
 * Extension of Router with caching system
 * Require memcached for run
 */
class RouterCached extends Router
{
    /**
     * @var object Cache resource
     */
    private $cache;

    /**
     * Constructor.
     *
     * @param CacheInterface $cache   Cache resource
     * @param array          $routes  List of registerd routes for the app in routes.php
     * @param array          $options Options for router config
     */
    public function __construct(CacheInterface $cache, array $routes = [], array $options = [])
    {
        //call parent constructor
        parent::__construct($routes, $options);

        //set cache resource
        $this->cache = $cache;
    }

    /**
     * Evaluate request uri.
     *
     * @param string $requestUri
     * @param string $requestMethod
     */
    public function validate(string $requestUri, string $requestMethod) : bool
    {
        //check if route is already cached
        if (($cachedRoute = $this->cache->get($requestUri.$requestMethod)) !== null) {
            //get cached route
            $this->route = $cachedRoute;

            return true;
        }

        //if route not cached, validate, if valid cache it
        if (parent::validate($requestUri, $requestMethod)) {
            //cache validated route
            $this->cache->set($requestUri.$requestMethod, $this->route, 3600);

            return true;
        }

        return false;
    }
}
