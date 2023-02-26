<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage\Connectors;

use Linna\Storage\AbstractConnector;
use RuntimeException;

/**
 * Mysql Improved Extension Connector.
 */
class PgConnector extends AbstractConnector
{
    /**
     * Return a new PgSql\Connection created using pg_connect() to intercat with Postgre database.
     *
     * @return object The <code>PgSql\Connection</code> instance.
     */
    public function getResource(): object
    {
        //check required because pg_connect is a function that returns a class or false
        if (($connection = \pg_connect(
            $this->options['connection_string'],
            $this->options['flags']
        )) === false) {
            throw new RuntimeException('Connection to Postgre using pg_connect() failed.');
        }

        return $connection;
    }
}
