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

namespace Leviu\Http;

/**
 * RouteInterface 
 * - Interface for routes.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 *
 * @since 0.1.0
 *
 * @version 0.1.0
 */
interface RouteInterface
{
    /**
     * Route contructor.
     * 
     * @param string $name
     * @param string $method
     * @param string $controller
     * @param string $action
     * @param array  $param
     *
     * @since 0.1.0
     */
    public function __construct($name, $method, $model, $view, $controller, $action, $param);

    /**
     * getType.
     * 
     * @since 0.1.0
     */
    public function getType();

    /**
     * getController.
     * 
     * @since 0.1.0
     */
    public function getController();

    /**
     * getAction.
     * 
     * @since 0.1.0
     */
    public function getAction();

    /**
     * getParam.
     * 
     * @since 0.1.0
     */
    public function getParam();
}
