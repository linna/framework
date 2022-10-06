<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage\Connectors;

use Linna\Storage\AbstractConnector;
use mysqli;

/**
 * Mysql Improved Extension Connector.
 */
class MysqliConnector extends AbstractConnector
{
    /**
     * Return a new Mysqli object to intercat with Mysql database.
     *
     * @return object The <code>mysqli</code> instance.
     */
    public function getResource(): object
    {
        \mysqli_report(MYSQLI_REPORT_ALL);

        return new mysqli(
            $this->options['host'],
            $this->options['user'],
            $this->options['password'],
            $this->options['database'],
            $this->options['port']
        );
    }
}
