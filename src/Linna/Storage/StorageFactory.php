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
 * Storage Factory
 */
class StorageFactory
{
    /**
     * Create Database Connection.
     * 
     * @param string $adapter
     * @param array $options
     * @return \Linna\Storage\MysqlPdoAdapter|\Linna\Storage\MysqliAdapter|\Linna\Storage\MongoDbAdapter
     * @throws InvalidArgumentException If requred adapter is not supported
     */
    public function createConnection(string $adapter, array $options) : StorageInterface
    {
        switch ($adapter) {
            case 'mysqlpdo':
                return new MysqlPdoAdapter($options);
            case 'mysqli':
                return new MysqliAdapter($options);
            case 'mongodb':
                return new MongoDbAdapter($options);
        }
        
        throw new InvalidArgumentException("$adapter not supported.");
    }
}