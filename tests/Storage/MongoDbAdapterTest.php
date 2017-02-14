<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
use Linna\Storage\MongoDbAdapter;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDbAdapterTest extends TestCase
{
    public function testConnection()
    {
        $mongoDbAdapter = new MongoDbAdapter();

        $this->assertInstanceOf(Client::class, $mongoDbAdapter->getResource());
    }

    public function testFailConnection()
    {
        $this->expectException(\Exception::class);

        $mongoDbAdapter = new MongoDbAdapter('mongodb:/localhost:27017');

        $resource = $mongoDbAdapter->getResource();
    }
}
