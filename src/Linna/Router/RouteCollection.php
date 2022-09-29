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

use Linna\TypedArrayObject\ArrayOfClasses;

/**
 * Route Collection.
 *
 * The set of routes used by the router to validate requests.
 */
class RouteCollection extends ArrayOfClasses
{
    /**
     * Class Contructor.
     *
     * @param array<Route> $array The array containing the routes used to create the collection.
     */
    public function __construct(array $array = [])
    {
        parent::__construct(Route::class, $array);
    }
}
