<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

use InvalidArgumentException;

/**
 * Storage Factory.
 */
class StorageFactory
{
    /**
     * @var string One of supported drivers
     */
    private $driver;

    /**
     * @var array Options for the driver
     */
    private $options;

    /**
     * Constructor.
     *
     * @param string $driver
     * @param array  $options
     */
    public function __construct(string $driver, array $options)
    {
        $this->driver = $driver;
        $this->options = $options;
    }

    /**
     * Create Database Connection.
     *
     * @throws InvalidArgumentException If requred adapter is not supported
     *
     * @return StorageInterface
     */
    public function getConnection() : StorageInterface
    {
        $driver = $this->driver;
        $options = $this->options;

        if ($driver === 'mysqlpdo') {
            return new MysqlPdoStorage($options['dsn'], $options['user'], $options['password'], $options['options']);
        }

        if ($driver === 'mysqli') {
            return new MysqliStorage($options['host'], $options['user'], $options['password'], $options['database'], $options['port']);
        }

        if ($driver === 'mongodb') {
            return new MongoDbStorage($options['uri'], $options['uriOptions'], $options['driverOptions']);
        }

        throw new InvalidArgumentException("[$driver] not supported.");
    }
}
