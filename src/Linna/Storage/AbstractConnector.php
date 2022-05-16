<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

/**
 * Abstract Connector.
 */
abstract class AbstractConnector implements ConnectorInterface
{
    /**
     * Constructor.
     *
     * @param array<mixed> $options
     */
    public function __construct(protected array $options = [])
    {
    }

    /**
     * Return resource to Database class.
     *
     * @return object
     */
    abstract public function getResource(): object;
}
