<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use Linna\Authentication\User;
use Linna\DataMapper\DomainObjectAbstract;

/**
 * Role domain object.
 */
class Role extends DomainObjectAbstract
{
    use PermissionTrait;

    /** @var string Group name. */
    public string $name = '';

    /** @var string Group description. */
    public string $description = '';

    /** @var int It say if group is active or not. */
    public int $active = 0;

    /**
     * Constructor.
     *
     * @param array<mixed> $users       Users in role.
     * @param array<mixed> $permissions Permissions granted by the role.
     */
    public function __construct(
        private array $users = [],
        array $permissions = []
    ) {
        $this->permission = $permissions;

        //set required type
        \settype($this->active, 'integer');
    }

    /**
     * Check if an user is in role, use <code>User</code> instance.
     *
     * @param User $user The user which will be checked as <code>User</code> instance.
     *
     * @return bool True if the user is in role, false otherwise.
     */
    public function isUserInRole(User $user): bool
    {
        return $this->isUserInRoleById($user->getId());
    }

    /**
     * Check if an user is in role, use the user id.
     *
     * @param int $userId The user which will be checked as user id.
     *
     * @return bool True if the user is in role, false otherwise.
     */
    public function isUserInRoleById(string|int $userId): bool
    {
        if (isset($this->users[$userId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if an user is in role, use the user name.
     *
     * @param string $userName The user which will be checked as user name.
     *
     * @return bool True if the user is in role, false otherwise.
     */
    public function isUserInRoleByName(string $userName): bool
    {
        if (\in_array($userName, \array_column($this->users, 'name'), true)) {
            return true;
        }

        return false;
    }
}
