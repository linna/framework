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
use MongoDB\Client;

/**
 * MongoDB Connector.
 */
class MongoDBConnector extends AbstractConnector
{
    /**
     * Get Resource.
     *
     * @return object
     */
    public function getResource(): object
    {
        return new Client(
            $this->options['uri'],
            $this->options['uriOptions'],
            $this->options['driverOptions']
        );
    }
}
