<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Mvc;

/**
 * Controller
 * - This is the parent class for every controller in the app, permit access
 * to view and models for every instance of a child.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Controller
{
    
    /**
     * @var object The model object for current controller
     */
    protected $model = null;

    /**
     * Controller constructor.
     * 
     * @param string $controller Passed for child __contruct is __CLASS__
     *
     * @since 0.1.0
     */
    public function __construct($model)//$controller)
    {
        $this->model = $model;
    }
}
