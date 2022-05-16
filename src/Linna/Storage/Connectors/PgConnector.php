<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage\Connectors;

use Linna\Storage\AbstractConnector;

/**
 * Mysql Improved Extension Connector.
 */
class PgConnector extends AbstractConnector
{
    /**
     * Get Resource.
     *
     * @return object
     */
    public function getResource(): object
    {
        return \pg_connect(
            $this->options['connection_string'],
            $this->options['flags']
        );
    }
}
