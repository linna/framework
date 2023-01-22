<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Router;

use Attribute;
use Closure;

/**
 * Describe valid routes.
 * <p>Implemented as data transfer object.</p>
 */
#[Attribute]
class Route implements RouteInterface
{
    /**
     * Class Constructor.
     *
     * @param string                   $method     Http method, mandatory parameter.
     * @param string                   $path       Route path, mandatory parameter.
     * @param string                   $name       Route name, optional parameter.
     * @param Closure|null             $callback   Route callback, optional parameter.
     * @param string                   $model      Mvc model, optional parameter.
     * @param string                   $view       Mvc view, optional parameter.
     * @param string                   $controller Mvc controller, optional parameter.
     * @param string                   $action     Mvc controller action, optional parameter.
     * @param bool                     $default    Default route, optional parameter.
     * @param array<int|string, mixed> $param      Route params, optional parameter.
     * @param string                   $allowedIps Allowed remote address, optional parameter.
     */
    public function __construct(

        /**
         * @var string The http method for which the route is valid.
         */
        public readonly string $method,

        /**
         * @var string The path part of the uri for which the route is valid.
         */
        public readonly string $path,

        /**
         * @var string The name of the route, useful for reverse routing.
         */
        public readonly string $name = '',

        /**
         * @var ?Closure The callback associated to the current route, if the
         *               route contains a reference to a model, to a view and
         *               to a controller, this parameter should be null.
         */
        public readonly ?Closure $callback = null,

        /**
         * @var string The class name of the model associated to the route, if
         *             the route contains a callback, this parameter should
         *             ignorated.
         */
        public readonly string $model = '',

        /**
         * @var string The class name of the view associated to the route, if
         *             the route contains a callback, this parameter should
         *             ignorated.
         */
        public readonly string $view = '',

        /**
         * @var string The class name of the controller associated to the route,
         *             if the route contains a callback, this parameter should
         *             ignorated.
         */
        public readonly string $controller = '',

        /**
         * @var string The name of the method for the controller associated to
         *             the route, if the route has no action, the default
         *             'entryPoint' method will be called, if the route contains
         *             a callback, this parameter should ignorated.
         */
        public readonly string $action = '',

        /**
         * @var bool Indicates if the route is the default route, such as the
         *           home page.
         */
        public readonly bool $default = false,

        /**
         * @var array<int|string, mixed> The parameters for the route, could be the param part of
         *                               the uri or parameters into the body of the request.
         */
        public readonly array $param = [],

        /**
         * @var string A list of ip addresses for which the route is valid,
         *             optional parameter.
         */
        public readonly string $allowedIps = '*'
    ) {
    }
}
