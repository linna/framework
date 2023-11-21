<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use DateTimeImmutable;
use Linna\Authentication\Password;

/**
 * User Extended, an user with permissions.
 */
class UserExtended extends User
{
    use PermissionTrait;
    use RoleTrait;

    /**
     * Class Constructor.
     *
     * @param Password               $passwordUtility <code>Password</code> class instance.
     * @param null|int|string        $id              User id.
     * @param string                 $uuid            Universal unique identifier.
     * @param string                 $name            User name.
     * @param string                 $description     User description.
     * @param string                 $email           User e-mail.
     * @param string                 $password        User hashed password. Use only to read it, not to set.
     * @param int                    $active          It says if user is active or not.
     * @param DateTimeImmutable|null $created         Creation datetime.
     * @param DateTimeImmutable|null $lastUpdate      Last updated datetime
     * @param array<mixed>           $roles           The roles granted to the user.
     * @param array<mixed>           $permissions     The permissions granted to the user.
     */
    public function __construct(
        /** @var Password Password class for manage password. */
        private Password $passwordUtility = new Password(),

        //user id
        null|int|string $id = null,

        /** @var string Universal unique identifier. */
        string $uuid = '',

        /** @var string User name. */
        string $name = '',

        /** @var string User description. */
        string $description = '',

        /** @var string User e-mail. */
        string $email = '',

        /** @var string User hashed password. Use only to read it, not to set.*/
        string $password = '',

        /** @var int It says if user is active or not. */
        int $active = 0,

        //creation datetime
        ?DateTimeImmutable $created = new DateTimeImmutable(),

        //last updated datetime
        ?DateTimeImmutable $lastUpdate = new DateTimeImmutable(),

        //roles granted to the user
        array $roles = [],

        //The permissions granted to the user
        array $permissions = []
    ) {
        //initialize parent
        parent::__construct(
            passwordUtility: $passwordUtility,
            id:              $id,
            uuid:            $uuid,
            name:            $name,
            description:     $description,
            password:        $password,
            active:          $active,
            created:         $created,
            lastUpdate:      $lastUpdate
        );

        //from role trait
        $this->role = $roles;

        //from permission trait
        $this->permission = $permissions;
    }
}
