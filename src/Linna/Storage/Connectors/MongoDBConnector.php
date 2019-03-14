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
use MongoDB\Client;

/**
 * MongoDB Connector.
 */
class MongoDBConnector extends AbstractConnector implements ConnectorInterface
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
