<?php

/**
 * Leviu.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace Leviu\Mvc;

use Leviu\Mvc\View;

/**
 * BaseController
 * - This is the parent class for every controller in the app, permit access
 * to view and models for every instance of a child.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Controller
{
    /**
     * @var object Database Connection
     */
    //protected $db = null;

    /**
     * @var object The model object for current controller
     */
    protected $model = null;

    /**
     * @var object The view objer for current controller
     */
    protected $view = null;

    /**
     * @var string Controller name for load correct model
     */
    protected $currentController = null;

    /**
     * Controller constructor.
     * 
     * @param string $controller Passed for child __contruct is __CLASS__
     *
     * @since 0.1.0
     */
    public function __construct($controller)
    {
        //connect to DB
        //$this->db = Database::connect();

        //store current controller
        $this->currentController = $controller;

        //get an instance of View object
        $this->view = new View();

        //get an instace of the proper model for current controller
        //$this->model = $this->loadModel();
    }

    /**
     * loadModel.
     * 
     * Load proper model for current controller or trow exception
     * Called into __construct()
     * 
     * @return \App_mk0\model Proper medel for loaded controller
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
