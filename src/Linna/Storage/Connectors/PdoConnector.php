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
use Linna\Storage\ExtendedPDO;

/**
 * PDO storage Connector.
 */
class PdoConnector extends AbstractConnector
{
    /**
     * Return a new <code>ExtendedPDO</code> object to intercat with persistent storage using PHP <code>PDO</code>.
     *
     * @return object The <code>ExtendedPDO</code> instance.
     */
    public function getResource(): object
    {
        return new ExtendedPDO(
            $this->options['dsn'],
            $this->options['user'],
            $this->options['password'],
            $this->options['options']
        );
    }
}
