<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

use Linna\DataMapper\DomainObjectAbstract;

/**
 * Login Attempt
 */
class LoginAttempt extends DomainObjectAbstract
{
    /**
     * @var string Session id.
     */
    public $sessionId;

    /**
     * @var string Remote IP v4 address.
     */
    public $ipv4;

    /**
     * @var string Remote IP v6 address.
     */
    public $ipv6;

    /**
     * @var string Show when login attempted.
     */
    public $when;
    
    /**
     * @var string Last update.
     */
    public $lastUpdate;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        settype($this->objectId, 'integer');
    }
}
