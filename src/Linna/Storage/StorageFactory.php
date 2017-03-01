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
        if ($adapter === 'mysqlpdo') { 
            return new MysqlPdoAdapter($options['dsn'], $options['user'], $options['password'], $options['options']);
        }
        
        if ($adapter === 'mysqli') { 
            return new MysqliAdapter($options['host'], $options['user'], $options['password'], $options['database'], $options['port']);
        }
        
        if ($adapter === 'mongodb') {
            return new MongoDbAdapter(
                $options['uri'],
                isset($options['uriOptions']) ? $options['uriOptions'] : [],
                isset($options['driverOptions']) ? $options['driverOptions'] : []
            );
        }
        
        throw new InvalidArgumentException("[$adapter] not supported.");
    }
}