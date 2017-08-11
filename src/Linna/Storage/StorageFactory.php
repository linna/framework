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

/**
 * Storage Factory.
 */
class StorageFactory extends AbstractStorageFactory
{
    /**
     * @var array Factory supported driver
     */
    protected $supportedDriver = [
        'pdo'     => PdoStorage::class,
        'mysqli'  => MysqliStorage::class,
        'mongodb' => MongoDbStorage::class,
    ];

    /**
     * Return Storage Resource.
     *
     * @return StorageInterface
     */
    public function get() : StorageInterface
    {
        return $this->returnStorageObject();
    }
}
