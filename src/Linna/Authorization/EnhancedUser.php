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
use Linna\Authentication\User;

/**
 * Enhanched User, an user with permissions.
 */
class EnhancedUser extends User
{
    use PermissionTrait;

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
        private array $roles = [],

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

        //from permission trait
        $this->permission = $permissions;
    }

    /**
     * Check if an user has a role, use Role instance.
     *
     * @param Role $role The role as <code>Role<code> object which will be checked.
     *
     * @return bool True if the user has the role, false otherwise.
     */
    public function hasRole(Role $role): bool
    {
        return $this->hasRoleById($role->getId());
    }

    /**
     * Check if an user has a role, use role Id.
     *
     * @param null|int|string $roleId The role as role id or uuid which will be checked.
     *
     * @return bool True if the user has the role, false otherwise.
     */
    public function hasRoleById(null|int|string $roleId): bool
    {
        if (isset($this->roles[$roleId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if an user has a role, use role name.
     *
     * @param string $roleName The role as role name which will be checked.
     *
     * @return bool True if the user has the role, false otherwise.
     */
    public function hasRoleByName(string $roleName): bool
    {
        if (\in_array($roleName, \array_column($this->roles, 'name'), true)) {
            return true;
        }

        return false;
    }
}
