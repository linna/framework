<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Database;

use Linna\Database\AdapterInterface;

/**
 * Database container
 *
 */
class Database
{
    /**
     * @var object $resource Contain adapter for database
     */
    private $resource;
    
    /**
     * Contructor
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->resource = $adapter->getResource();
    }

    /**
     * Get connection
     *
     * @return object
     */
    public function connect()
    {
        return $this->resource;
    }
}
