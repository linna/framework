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

use Linna\Storage\Connectors\PgConnector;
use PHPUnit\Framework\TestCase;
use PgSql\Connection;

/**
 * Pg Connector Test
 */
class PgConnectorTest extends TestCase
{
    /**
     * Test connection.
     *
     * @return void
     */
    public function testConnection(): void
    {
        $options = [
            'connection_string' => $GLOBALS['pgsql_connection_string'],
            'flags'             => 0,
        ];

        $this->assertInstanceOf(Connection::class, (new PgConnector($options))->getResource());
    }
}
