<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage\Connectors;

use Linna\Storage\AbstractConnector;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\ConnectorInterface;

/**
 * PDO storage Connector.
 */
class PdoConnector extends AbstractConnector implements ConnectorInterface
{
    /**
     * Get Resource.
     *
     * @return ExtendedPDO
     */
    public function getResource()
    {
        return new ExtendedPDO(
            $this->options['dsn'],
            $this->options['user'],
            $this->options['password'],
            $this->options['options']
        );
    }
}
