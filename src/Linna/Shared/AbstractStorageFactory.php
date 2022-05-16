<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Shared;

use InvalidArgumentException;

/**
 * Storage Factory.
 */
abstract class AbstractStorageFactory
{
    /**
     * @var array<string> Factory supported driver
     */
    protected array $supportedDriver = [];

    /**
     * Constructor.
     *
     * @param string       $driver
     * @param array<mixed> $options
     */
    public function __construct(protected string $driver, protected array $options = [])
    {
    }

    /**
     * Return Storage Object.
     *
     * @throws InvalidArgumentException If required driver is not supported
     *
     * @return mixed
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
     * Get storage object.
     *
     * @return mixed
     */
    abstract public function get();
}
