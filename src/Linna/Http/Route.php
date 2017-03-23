<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Http;

/**
 * Describe valid routes.
 */
class Route implements RouteInterface
{
    /**
     * @var string Route name
     */
    protected $name;

    /**
     * @var string Indicates request method
     */
    protected $method;

    /**
     * @var string View to call
     */
    protected $view;

    /**
     * @var string View to call
     */
    protected $model;

    /**
     * @var string Controller to call
     */
    protected $controller;

    /**
     * @var string Action to call
     */
    protected $action;

    /**
     * @var array Parameter passed to controller
     */
    protected $param;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $method
     * @param string $model
     * @param string $view
     * @param string $controller
     * @param mixed  $action
     * @param array  $param
     */
    public function __construct(string $name, string $method, string $model, string $view, string $controller, string $action, array $param)
    {
        $this->name = $name;
        $this->method = $method;
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
        $this->action = $action;
        $this->param = $param;
    }

    /**
     * Return model name.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Return view name.
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Return controller.
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Return action name.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Return parameters.
     *
     * @return array
     */
    public function getParam(): array
    {
        return $this->param;
    }
}
