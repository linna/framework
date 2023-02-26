<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage;

use Linna\Shared\AbstractStorageFactory;
use Linna\Storage\Connectors\PdoConnector;
use Linna\Storage\Connectors\PgConnector;
use Linna\Storage\Connectors\MongoDBConnector;
use Linna\Storage\Connectors\MysqliConnector;

/**
 * Storage Factory.
 */
class StorageFactory extends AbstractStorageFactory
{
    /** @var array<string> Factory supported driver */
    protected array $supportedDriver = [
        'pdo'     => PdoConnector::class,
        'pg'      => PgConnector::class,
        'mongodb' => MongoDBConnector::class,
        'mysqli'  => MysqliConnector::class,
    ];

    /**
     * Return a resource or an object to intercat with a persistent storage.
     *
     * @return object The specific storage resource or the object to interact with the persistent storage.
     */
    public function get(): object
    {
        return $this->returnStorageObject()->getResource();
    }
}
