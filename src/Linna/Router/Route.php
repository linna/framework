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
    public string $name = '';

    /**
     * @var string Route method.
     */
    public string $method = '';

    /**
     * @var string Route url.
     */
    public string $url = '';

    /**
     * @var string Route model.
     */
    public string $model = '';

    /**
     * @var string Route view.
     */
    public string $view = '';

    /**
     * @var string Route controller.
     */
    public string $controller = '';

    /**
     * @var string Route action.
     */
    public string $action = '';

    /**
     * @var bool Route default.
     */
    public bool $default = false;

    /**
     * @var array Route parameters.
     */
    public array $param = [];

    /**
     * @var callable Route callback.
     */
    public $callback;

    /**
     * @var string Ip address from which the route is callable, ip address
     *             format is intentionally not specified.
     */
    public string $allowed = '*';

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
        ] = \array_replace_recursive([
            'name'       => $this->name,
            'method'     => $this->method,
            'url'        => $this->url,
            'model'      => $this->model,
            'view'       => $this->view,
            'controller' => $this->controller,
            'action'     => $this->action,
            'default'    => $this->default,
            'param'      => $this->param,
            'callback'   => $this->callback,
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
        if (\is_callable($this->callback)) {
            return $this->callback;
        }

        return function () {
        };
    }

    /**
     * Return Ip addresses from which the route is callable.
     *
     * @return string
     */
    public function getAllowed(): string
    {
        return $this->allowed;
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
