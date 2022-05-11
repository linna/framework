<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Mvc;

/**
 * This is the parent class for every controller in the app, permit access
 * to view and models for every instance of a child.
 */
class Controller
{
    /**
     * @var Model The model object for current controller
     */
    protected Model $model;

    /**
     * Class Constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
