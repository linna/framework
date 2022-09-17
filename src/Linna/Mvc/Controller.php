<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Mvc;

/**
 * This is the parent class for every controller in the app, permit access
 * to view and models for every instance of a child.
 */
class Controller
{
    /**
     * Class Constructor.
     *
     * @param Model $model
     */
    public function __construct(protected Model $model)
    {
    }
}
