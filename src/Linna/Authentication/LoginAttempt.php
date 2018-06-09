<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
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
     * @var string User name.
     */
    public $userName;

    /**
     * @var string Session id.
     */
    public $sessionId;

    /**
     * @var string Remote IP address.
     */
    public $ipAddress;

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
