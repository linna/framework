<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
     * Constructor
     *
     * @param string $name
     * @param string $method
     * @param string $model
     * @param string $view
     * @param string $controller
     * @param mixed $action
     * @param array $param
     */
    public function __construct(string $name, string $method, string $model, string $view, string $controller, string $action, array $param);

    /**
     * Return model name
     *
     */
    public function getModel(): string;
    
    /**
     * Return view name
     *
     */
    public function getView(): string;
    
    /**
     * Return controller
     *
     */
    public function getController(): string;

    /**
     * Return action name
     *
     */
    public function getAction(): string;

    /**
     * Return parameters
     *
     */
    public function getParam(): array;
}
