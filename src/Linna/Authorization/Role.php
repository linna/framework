<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

use Linna\Authentication\User;
use Linna\DataMapper\DomainObjectAbstract;

/**
 * Role.
 */
class Role extends DomainObjectAbstract
{
    use PermissionTrait;

    /**
     * @var string Group name
     */
    public string $name = '';

    /**
     * @var string Group description
     */
    public string $description = '';

    /**
     * @var int It say if group is active or not
     */
    public int $active = 0;

    /**
     * @var array<mixed> Contain users in group
     */
    private array $users = [];

    /**
     * Constructor.
     *
     * @param array<mixed> $users
     * @param array<mixed> $permissions
     */
    public function __construct(array $users = [], array $permissions = [])
    {
        $this->users = $users;
        $this->permission = $permissions;

        //set required type
        \settype($this->id, 'integer');
        \settype($this->active, 'integer');
    }

    /**
     * Check if an user is in role, use User instance.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isUserInRole(User $user): bool
    {
        return $this->isUserInRoleById($user->getId());
    }

    /**
     * Check if an user is in role, use the user Id.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isUserInRoleById(int $userId): bool
    {
        if (isset($this->users[$userId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if an user is in role, use the user name.
     *
     * @param string $userName
     *
     * @return bool
     */
    public function isUserInRoleByName(string $userName): bool
    {
        if (\in_array($userName, \array_column($this->users, 'name'), true)) {
            return true;
        }

        return false;
    }
}
