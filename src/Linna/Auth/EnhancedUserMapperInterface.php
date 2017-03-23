<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

use Linna\DataMapper\MapperInterface;

/**
 * User Mapper Interface
 * Contain methods required from concrete User Mapper.
 */
interface EnhancedUserMapperInterface extends MapperInterface
{
    /**
     * Fetch users by role
     * From role id as argument, this method must return an array containing
     * a User object instance for every user that belongs at the
     * given role.
     *
     * @param int $roleId
     *
     * @return array
     */
    public function fetchUserByRole(int $roleId) : array;

    /**
     * Fetch users by permission
     * From permission id as argument, this method must return an array containing
     * a User object instance for every user that have the
     * given permission.
     *
     * @param int $permissionId
     *
     * @return array
     */
    public function fetchUserByPermission(int $permissionId) : array;

    /**
     * Grant a permission to an user
     * This method must insert new user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions()
     * method.
     * Remind to pass all user's permission to EnhancedUser->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param EnhancedUser $user
     * @param string       $permission
     */
    public function grant(EnhancedUser &$user, string $permission);

    /**
     * Revoke a permission to an user
     * This method must remove user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions()
     * method.
     * As previous method remind to pass all user's permission to
     * EnhancedUser->setPermissions(), when write concrete mapper is
     * well pass PermissionMapper as constructor dependency.
     *
     * @param EnhancedUser $user
     * @param string       $permission
     */
    public function revoke(EnhancedUser &$user, string $permission);
}
