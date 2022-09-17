<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use Linna\DataMapper\DomainObjectAbstract;

/**
 * Login Attempt domain object.
 */
class LoginAttempt extends DomainObjectAbstract
{
    /** @var string The user name. */
    public string $userName;

    /** @var string The session id used by the user. */
    public string $sessionId;

    /** @var string The IP address used by the user */
    public string $ipAddress;

    /** @var string The date time of the login attempt. */
    public string $when;

    /**
     * Class Constructor.
     */
    public function __construct(

    ) {
    }
}
