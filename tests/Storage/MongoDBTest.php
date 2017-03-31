<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
use Linna\Storage\MongoDbObject;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDbObjectTest extends TestCase
{
    public function testConnection()
    {
        $mongoDbAdapter = new MongoDbObject();

        $this->assertInstanceOf(Client::class, $mongoDbAdapter->getResource());
    }

    public function testFailConnection()
    {
        $this->expectException(\Exception::class);

        $mongoDbAdapter = new MongoDbObject('mongodb:/localhost:27017');

        $resource = $mongoDbAdapter->getResource();
    }
}
