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
use MongoDB\Client;

/**
 * MongoDB Connector.
 */
class MongoDBConnector extends AbstractConnector
{
    /**
     * Return a new MongoDB\Client object to intercat with MongoDB database.
     *
     * @return object The <code>MongoDB\Client</code> instance.
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
