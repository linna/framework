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
     *
     * @var array Factory supported driver 
     */
    private $supportedDriver = [
        'mysqlpdo' => MysqlPdoStorage::class,
        'pgsqlpdo' => PostgresqlPdoStorage::class,
        'mysqli' => MysqliStorage::class,
        'mongodb' => MongoDbStorage::class
    ];
        
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
     * @throws InvalidArgumentException If required driver is not supported
     *
     * @return StorageInterface
     */
    public function getConnection() : StorageInterface
    {
        $driver = $this->driver;
        $options = $this->options;

        if (isset($this->supportedDriver[$driver])){
            $storageClass = $this->supportedDriver[$driver];

            return new $storageClass($options);
        }

        throw new InvalidArgumentException("[$driver] not supported.");
    }
}
