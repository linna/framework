<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

/**
 * Describe valid routes.
 */
class Route implements RouteInterface
{
    /**
     * @var string Route name.
     */
    public $name = '';

    /**
     * @var string Route method.
     */
    public $method = '';

    /**
     * @var string Route url.
     */
    public $url = '';

    /**
     * @var string Route model.
     */
    public $model = '';

    /**
     * @var string Route view.
     */
    public $view = '';

    /**
     * @var string Route controller.
     */
    public $controller = '';

    /**
     * @var string Route action.
     */
    public $action = '';

    /**
     * @var bool Route default.
     */
    public $default = false;

    /**
     * @var array Route parameters.
     */
    public $param = [];

    /**
     * @var mixed Route callback.
     */
    public $callback;

    /**
     * Constructor.
     *
     * @param array $route
     */
    public function __construct(array $route = [])
    {
        [
            'name'       => $this->name,
            'method'     => $this->method,
            'url'        => $this->url,
            'model'      => $this->model,
            'view'       => $this->view,
            'controller' => $this->controller,
            'action'     => $this->action,
            'default'    => $this->default,
            'param'      => $this->param,
            'callback'   => $this->callback
        ] = array_replace_recursive([
            'name'       => '',
            'method'     => '',
            'url'        => '',
            'model'      => '',
            'view'       => '',
            'controller' => '',
            'action'     => '',
            'default'    => false,
            'param'      => [],
            'callback'   => null,
        ], $route);
    }

    /**
     * Return route name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return route method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Return route url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
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

    /**
     * Return if route is set as default.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * Return route callback.
     *
     * @return callable
     */
    public function getCallback(): callable
    {
        if (is_callable($this->callback)) {
            return $this->callback;
        }

        return function () {
        };
    }

    /**
     * Return route array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'method' => $this->method,
            'url' => $this->url,
            'model' => $this->model,
            'view' => $this->view,
            'controller' => $this->controller,
            'action' => $this->action,
            'default' => $this->default,
            'param' => $this->param,
            'callback' => $this->callback
        ];
    }
}
