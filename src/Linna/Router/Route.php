<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

/**
 * Describe valid routes.
 * Implemented as data transfer object.
 */
class Route implements RouteInterface
{
    /**
     * Class Constructor.
     *
     * @param string        $method         http method
     * @param string        $path           route path
     * @param Closure|null  $callback       route callback
     * @param string        $model          mvc model
     * @param string        $view           mvc view
     * @param string        $controller     mvc controller
     * @param string        $name           route name
     * @param string        $action         mvc controller action
     * @param bool          $default        default route
     * @param array         $param          route params
     * @param string        $allowedIps     allowed remote address
     */
    public function __construct(
        public readonly string $method,     // mandatory
        public readonly string $path,       // mandatory

        // callback or mvc
        public readonly ?Closure $callback = null,
        // callback or mvc
        public readonly string $model = '',
        public readonly string $view = '',
        public readonly string $controller = '',

        // default properties
        public readonly string $name = '',
        public readonly string $action = '',
        public readonly bool $default = false,
        public readonly array $param = [],
        public readonly string $allowedIps = '*'
    ) {
    }
}
