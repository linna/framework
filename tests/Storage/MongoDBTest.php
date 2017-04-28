<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
use Linna\Storage\MongoDbStorage;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDbStorageTest extends TestCase
{
    public function testConnection()
    {
        $mongoDbAdapter = new MongoDbStorage();

        $this->assertInstanceOf(Client::class, $mongoDbAdapter->getResource());
    }

    /**
     * @expectedException Exception
     */
    public function testFailConnection()
    {
        //$this->expectException(\Exception::class);
        
        (new MongoDbStorage('mongodb:/localhost:27017'))->getResource();
        /*$mongoDbAdapter = new MongoDbStorage('mongodb:/localhost:27017');

        $resource = $mongoDbAdapter->getResource();*/
    }
}
