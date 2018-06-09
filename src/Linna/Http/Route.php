<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Http;

/**
 * Describe valid routes.
 */
class Route implements RouteInterface
{
    /**
     * @var array Route default vales
     */
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
     * Return route name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->route['name'];
    }

    /**
     * Return route method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->route['method'];
    }

    /**
     * Return route url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->route['url'];
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
    public function isDefault(): bool
    {
        return $this->route['default'];
    }

    /**
     * Return route callback.
     *
     * @return callable
     */
    public function getCallback(): callable
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
    public function toArray(): array
    {
        return $this->route;
    }
}
