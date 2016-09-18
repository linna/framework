<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Mvc;

/**
 * This is the parent class for every controller in the app, permit access
 * to view and models for every instance of a child.
 *
 */
class Controller
{
    /**
     * @var object $model The model object for current controller
     */
    protected $model = null;

    /**
     * Controller constructor.
     *
     * @param object $model The model object for current controller
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
