<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\Connectors\MongoDBConnector;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

/**
 * MongoDB Connector Test
 */
class MongoDBConnectorTest extends TestCase
{
    /**
     * Test connection.
     */
    public function testConnection(): void
    {
        $options = [
            'uri'           => 'mongodb://127.0.0.1/',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        $this->assertInstanceOf(Client::class, (new MongoDBConnector($options))->getResource());
    }

    /**
     * Test fail connenction.
     *
     * @expectedException InvalidArgumentException
     */
    public function testFailConnection(): void
    {
        $options = [
            'uri'           => 'mongodb:/localhost:27017',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        (new MongoDBConnector($options))->getResource();
    }
}
