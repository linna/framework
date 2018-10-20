<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

use Linna\Authentication\User;
use Linna\DataMapper\MapperInterface;

/**
 * Group Mapper Interface
 * Contain methods required from concrete Group Mapper.
 */
interface RoleMapperInterface extends MapperInterface
{
    /**
     * Fetch permissions inherited by a user from a role
     * From Role and User objects instances as arguments, this method must return an array containing
     * a Permission object instance for every permission owned by the
     * given user user and role.
     *
     * @param Role $role
     * @param User $user
     *
     * @return array
     */
    public function fetchUserInheritedPermissions(Role &$role, User $user): array;

    /**
     * Grant a permission at role
     * This method must insert new role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * Remind to pass all role's permission to Role->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param Role   $role
     * @param string $permission
     */
    public function permissionGrant(Role &$role, string $permission);

    /**
     * Revoke a permission at role
     * This method must remove role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * As previous method remind to pass all role's permission
     * to Role->setPermissions(), when write concrete mapper is well
     * pass PermissionMapper as constructor dependency.
     *
     * @param Role   $role
     * @param string $permission
     */
    public function permissionRevoke(Role &$role, string $permission);

    /**
     * Add role at an user
     * This method must insert new user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * Remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role $role
     * @param User $user
     */
    public function userAdd(Role &$role, User $user);

    /**
     * Remove user from a role
     * This method must remove user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * As previous method remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role $role
     * @param User $user
     */
    public function userRemove(Role &$role, User $user);
}
