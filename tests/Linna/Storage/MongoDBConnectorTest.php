<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage;

use InvalidArgumentException;
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
     *
     * @return void
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
     * @return void
     */
    public function testFailConnection(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $options = [
            'uri'           => 'mongodb:/localhost:27017',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        (new MongoDBConnector($options))->getResource();
    }
}
