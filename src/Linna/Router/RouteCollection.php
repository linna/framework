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

use Linna\TypedArrayObject\ArrayOfClasses;

/**
 * Route Collection
 */
class RouteCollection extends ArrayOfClasses
{
    /**
     * Class Contructor.
     *
     * @param array<Route> $array
     */
    public function __construct(array $array = [])
    {
        parent::__construct(Route::class, $array);
    }
}
