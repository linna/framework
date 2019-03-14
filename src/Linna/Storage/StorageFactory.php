<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

use Linna\Storage\Connectors\PdoConnector;
use Linna\Storage\Connectors\MongoDBConnector;
use Linna\Storage\Connectors\MysqliConnector;

/**
 * Storage Factory.
 */
class StorageFactory extends AbstractStorageFactory
{
    /**
     * @var array Factory supported driver
     */
    protected $supportedDriver = [
        'pdo'     => PdoConnector::class,
        'mongodb' => MongoDBConnector::class,
        'mysqli'  => MysqliConnector::class,
    ];

    /**
     * Return Storage Resource.
     *
     * @return object
     */
    public function get(): object
    {
        return $this->returnStorageObject()->getResource();
    }
}
