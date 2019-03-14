<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage\Connectors;

use Linna\Storage\AbstractConnector;
use Linna\Storage\ConnectorInterface;
use mysqli;

/**
 * Mysql Improved Extension Connector.
 */
class MysqliConnector extends AbstractConnector implements ConnectorInterface
{
    /**
     * Get Resource.
     *
     * @return object
     */
    public function getResource(): object
    {
        mysqli_report(MYSQLI_REPORT_ALL);

        return new mysqli(
            $this->options['host'],
            $this->options['user'],
            $this->options['password'],
            $this->options['database'],
            $this->options['port']
        );
    }
}
