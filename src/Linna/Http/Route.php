<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Http;

/**
 * Describe valid routes.
 */
class Route implements RouteInterface
{
    protected $route = [
        'name'       => '',
        'method'     => '',
        'url'        => '',
        'model'      => '',
        'view'       => '',
        'controller' => '',
        'action'     => '',
        'default'    => false,
        'param'      => [],
        'callback'   => false,
    ];

    /**
     * Constructor.
     *
     * @param array $route
     */
    public function __construct(array $route = [])
    {
        $this->route = array_replace_recursive($this->route, $route);
    }

    /**
     * Return model name.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->route['model'];
    }

    /**
     * Return view name.
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->route['view'];
    }

    /**
     * Return controller.
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->route['controller'];
    }

    /**
     * Return action name.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->route['action'];
    }

    /**
     * Return parameters.
     *
     * @return array
     */
    public function getParam(): array
    {
        return $this->route['param'];
    }

    /**
     * Return if route is set as default.
     *
     * @return bool
     */
    public function isDefault() : bool
    {
        return $this->route['dafault'];
    }

    /**
     * Return route callback.
     *
     * @return callable
     */
    public function getCallback() : callable
    {
        if (is_callable($this->route['callback'])) {
            return $this->route['callback'];
        }

        return function () {
        };
    }

    /**
     * Return route array.
     *
     * @return array
     */
    public function getArray(): array
    {
        return $this->route;
    }
}
