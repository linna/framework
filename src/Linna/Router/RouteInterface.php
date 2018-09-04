<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Router;

/**
 * Interface for routes.
 */
interface RouteInterface
{
    /**
     * Constructor.
     *
     * @param array $route
     */
    public function __construct(array $route);

    /**
     * Return route array.
     *
     * @return array
     */
    public function toArray(): array;
}
