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
     * @param null|int|string        $id         Login attempt id.
     * @param string                 $userName   User name used in login.
     * @param string                 $sessionId  Session id of the request.
     * @param string                 $ipAddress  Ip address of the request.
     * @param DateTimeImmutable|null $when       Login time.
     * @param DateTimeImmutable|null $created    Creation datetime.
     * @param DateTimeImmutable|null $lastUpdate Last updated datetime.
     */
    public function __construct(
        //user id
        null|int|string $id = null,

        /** @var string The user name. */
        public string $userName = '',

        /** @var string The session id used by the user. */
        public string $sessionId = '',

        /** @var string The IP address used by the user */
        public string $ipAddress = '',

        /** @var DateTimeImmutable|null The date time of the login attempt. */
        public ?DateTimeImmutable $when = null,

        //creation datetime
        ?DateTimeImmutable $created = new DateTimeImmutable(),

        //last updated datetime
        ?DateTimeImmutable $lastUpdate = new DateTimeImmutable()
    ) {
        //parent properties
        $this->id = $id;
        $this->created = $created;
        $this->lastUpdate = $lastUpdate;
    }
}
