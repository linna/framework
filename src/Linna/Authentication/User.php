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
 * User domain object.
 *
 * <p>Provide a basic user for authentication system, the class is thinked to be used with PHP <code>PDO</code>, but
 * should be instantiated like a normal class.</p>
 */
class User extends DomainObjectAbstract
{
    /**
     * Class Constructor.
     *
     * @param Password $password <code>Password</code> class instance.
     */
    public function __construct(
        /** @var Password Password class for manage password. */
        private Password $passwordUtility = new Password(),

        //user id
        null|int|string $id = null,

        /** @var string Universal unique identifier. */
        public string $uuid = '',

        /** @var string User name. */
        public string $name = '',

        /** @var string User description. */
        public ?string $description = null,

        /** @var string User e-mail. */
        public ?string $email = null,

        /** @var string User hashed password. Use only to read it, not to set.*/
        public string $password = '',

        /** @var int It say if user is active or not. */
        public int $active = 0,

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

    /**
     * Set new user password without do any check.
     *
     * @param string $newPassword User new password.
     *
     * @return void
     */
    public function setPassword(string $newPassword): void
    {
        //hash provided password
        $this->password = $this->passwordUtility->hash($newPassword);
    }

    /**
     * Change user password only after check if old password is correct.
     *
     * @param string $newPassword User new password.
     * @param string $oldPassword User old password.
     *
     * @return bool True if password change done, false otherwise.
     */
    public function changePassword(string $newPassword, string $oldPassword): bool
    {
        //verfy password match
        if ($this->passwordUtility->verify($oldPassword, $this->password)) {
            //if match set new password
            $this->password = $this->passwordUtility->hash($newPassword);

            return true;
        }

        return false;
    }
}
