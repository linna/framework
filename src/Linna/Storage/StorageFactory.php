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
     * Create Database Connection.
     *
     * @param string $adapter
     * @param array  $options
     *
     * @throws InvalidArgumentException If requred adapter is not supported
     *
     * @return \Linna\Storage\MysqlPdoObject|\Linna\Storage\MysqliObject|\Linna\Storage\MongoDbObject
     */
    public function createConnection(string $adapter, array $options) : StorageObjectInterface
    {
        if ($adapter === 'mysqlpdo') {
            return new MysqlPdoObject($options['dsn'], $options['user'], $options['password'], $options['options']);
        }

        if ($adapter === 'mysqli') {
            return new MysqliObject($options['host'], $options['user'], $options['password'], $options['database'], $options['port']);
        }

        if ($adapter === 'mongodb') {
            return new MongoDbObject($options['uri'], $options['uriOptions'], $options['driverOptions']);
        }

        throw new InvalidArgumentException("[$adapter] not supported.");
    }
}
