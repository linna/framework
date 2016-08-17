<?php

/**
 * Leviu.
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
namespace Leviu\Mvc;

use Leviu\Mvc\View;

/**
 * This is the parent class for every controller in the app, permit access
 * to view and models.
 */
class Controller
{
    /**
     * @var object $model The model object for current controller
     */
    protected $model = null;

    /**
     * @var object $view The view objer for current controller
     */
    protected $view = null;

    /**
     * @var string $currentController Controller name for load correct model
     */
    protected $currentController = null;

    /**
     * Constructor.
     * 
     * @param string $controller Passed for child __contruct is __CLASS__
     *
     * @since 0.1.0
     */
    public function __construct($controller)
    {
        //store current controller
        $this->currentController = $controller;

        //get an instance of View object
        $this->view = new View();

        //get an instace of the proper model for current controller
        //$this->model = $this->loadModel();
    }

    /**
     * Load proper model for current controller or trow exception
     * 
     * @return \Model 
     *
     * @throws \Exception if model not exist
     *
     * @since 0.1.0
     */
    protected function loadModel()
    {
        $model = str_replace('Controllers', 'Models', $this->currentController);

        try {
            if (!class_exists($model)) {
                throw new \Exception("The required Model ({$model}) not exist.");
            }

            return new $model();
        } catch (\Exception $e) {
            echo 'Model exception: ', $e->getMessage(), "\n";
            die();
        }
    }
}
