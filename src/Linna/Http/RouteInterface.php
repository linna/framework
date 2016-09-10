<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Http;

/**
 * Interface for routes.
 *
 */
interface RouteInterface
{
    /**
     * Contructor
     *
     * @param string $name
     * @param string $method
     * @param string $controller
     * @param string $action
     * @param array  $param
     */
    public function __construct($name, $method, $model, $view, $controller, $action, $param);

    /**
     * Return controller
     *
     */
    public function getController();

    /**
     * Return action name
     *
     */
    public function getAction();

    /**
     * Return parameters
     *
     */
    public function getParam();
    
    /**
     * Return model name
     *
     */
    public function getModel();
    
    /**
     * Return view name
     *
     */
    public function getView();
}
