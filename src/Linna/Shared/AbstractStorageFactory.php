<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Shared;

use InvalidArgumentException;

/**
 * Storage Factory.
 */
abstract class AbstractStorageFactory
{
    /** @var array<string> Factory supported driver */
    protected array $supportedDriver = [];

    /**
     * Class Constructor.
     *
     * @param string       $driver  The driver used to connect to the persistent storage.
     * @param array<mixed> $options Driver options.
     */
    public function __construct(
            /** @var string The driver used to connect to the persistent storage. */
            protected string $driver,

            /** @var array<mixed> Driver options. */
            protected array $options = []
    ) {
    }

    /**
     * Return Storage Object.
     *
     * @throws InvalidArgumentException If required driver is not supported.
     *
     * @return mixed The connector object to obtain the resource to interact with the persistent storage.
     */
    protected function returnStorageObject()
    {
        $driver = $this->driver;
        $options = $this->options;

        if (isset($this->supportedDriver[$driver])) {
            $class = $this->supportedDriver[$driver];

            return new $class($options);
        }

        throw new InvalidArgumentException("[{$driver}] not supported.");
    }

    /**
     * Return Storage Resource.
     *
     * @return object The specific storage resource to interact with the persistent storage.
     */
    abstract public function get(): object;
}
