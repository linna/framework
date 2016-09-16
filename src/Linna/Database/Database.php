<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Database;

use Linna\Database\AdapterInterface;

class Database
{
    private $resource;
    
    public function __construct(AdapterInterface $adapter)
    {
        $this->resource = $adapter->getResource();
    }

    public function connect()
    {
        return $this->resource;
    }
}
