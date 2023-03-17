<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use DateTimeImmutable;
use Linna\DataMapper\DomainObjectAbstract;

/**
 * Login Attempt domain object.
 */
class LoginAttempt extends DomainObjectAbstract
{
    /**
     * Class Constructor.
     *
     * @param null|int|string        $id
     * @param string                 $userName
     * @param string                 $sessionId
     * @param string                 $ipAddress
     * @param DateTimeImmutable|null $when
     * @param DateTimeImmutable|null $created
     * @param DateTimeImmutable|null $lastUpdate
     */
    public function __construct(
        null|int|string $id = null,

        /** @var string The user name. */
        public string $userName = '',

        /** @var string The session id used by the user. */
        public string $sessionId = '',

        /** @var string The IP address used by the user */
        public string $ipAddress = '',

        /** @var DateTimeImmutable|null The date time of the login attempt. */
        public ?DateTimeImmutable $when = null,
        ?DateTimeImmutable $created = null,
        ?DateTimeImmutable $lastUpdate = null
    ) {
        //parent properties
        $this->id = $id;
        $this->created = $created;
        $this->lastUpdate = $lastUpdate;
    }
}
