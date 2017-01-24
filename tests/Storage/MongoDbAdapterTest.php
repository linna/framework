<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use MongoDB\Client;
use Linna\Storage\MongoDbAdapter;
use PHPUnit\Framework\TestCase;

class MongoDbAdapterTest extends TestCase
{
    public function testConnection()
    {
        $mongoDbAdapter = new MongoDbAdapter($GLOBALS['mongodb_server_string']);
        
        $this->assertInstanceOf(Client::class, $mongoDbAdapter->getResource());
    }
}
