<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

/**
 * Describe null routes.
 */
class NullRoute implements RouteInterface
{
    /**
     * Constructor.
     *
     * @param array $route
     */
    public function __construct(array $route = [])
    {
        unset($route);
    }

    /**
     * Return route array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }
}
