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

/**
 * MongoDB Storage Test
 */
class MongoDbStorageTest extends TestCase
{
    /**
     * Test connection.
     */
    public function testConnection()
    {
        $options = [
            'uri'           => 'mongodb://127.0.0.1/',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        $this->assertInstanceOf(Client::class, (new MongoDbStorage($options))->getResource());
    }

    /**
     * Test fail connenction.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFailConnection()
    {
        $options = [
            'uri'           => 'mongodb:/localhost:27017',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        (new MongoDbStorage($options))->getResource();
    }
}
