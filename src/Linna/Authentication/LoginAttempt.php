<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
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
    public string $userName = '';

    /**
     * @var string Session id.
     */
    public string $sessionId = '';

    /**
     * @var string Remote IP address.
     */
    public string $ipAddress = '';

    /**
     * @var string Show when login attempted.
     */
    public string $when = '';

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        \settype($this->id, 'integer');
    }
}
