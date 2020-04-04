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

use Linna\Authorization\EnhancedUser;
use Linna\Authorization\Permission;
use Linna\Authorization\Role;
use Linna\DataMapper\MapperInterface;

/**
 * Group Mapper Interface
 * Contain methods required from concrete Group Mapper.
 */
interface RoleMapperInterface extends MapperInterface, FetchByPermissionInterface, FetchByUserInterface
{
    /**
     * Grant a permission at role
     * This method must insert new role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * Remind to pass all role's permission to Role->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param Role          $role
     * @param Permission    $permission
     *
     * @return void
     */
    public function grantPermission(Role &$role, Permission $permission);

    /**
     * Grant a permission at role
     * This method must insert new role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * Remind to pass all role's permission to Role->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param Role  $role
     * @param int   $permissionId
     *
     * @return void
     */
    public function grantPermissionById(Role &$role, int $permissionId);

    /**
     * Grant a permission at role
     * This method must insert new role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * Remind to pass all role's permission to Role->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param Role      $role
     * @param string    $permissionName
     *
     * @return void
     */
    public function grantPermissionByName(Role &$role, string $permissionName);

    /**
     * Revoke a permission at role
     * This method must remove role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * As previous method remind to pass all role's permission
     * to Role->setPermissions(), when write concrete mapper is well
     * pass PermissionMapper as constructor dependency.
     *
     * @param Role          $role
     * @param Permission    $permission
     *
     * @return void
     */
    public function revokePermission(Role &$role, Permission $permission);

    /**
     * Revoke a permission at role
     * This method must remove role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * As previous method remind to pass all role's permission
     * to Role->setPermissions(), when write concrete mapper is well
     * pass PermissionMapper as constructor dependency.
     *
     * @param Role  $role
     * @param int   $permissionId
     *
     * @return void
     */
    public function revokePermissionById(Role &$role, int $permissionId);

    /**
     * Revoke a permission at role
     * This method must remove role-permission coupling in persistent
     * storage and update Role calling Role->setPermissions() method.
     * As previous method remind to pass all role's permission
     * to Role->setPermissions(), when write concrete mapper is well
     * pass PermissionMapper as constructor dependency.
     *
     * @param Role      $role
     * @param string    $permissionName
     *
     * @return void
     */
    public function revokePermissionByName(Role &$role, string $permissionName);

    /**
     * Add an user to a role
     * This method must insert new user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * Remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role          $role
     * @param EnhancedUser  $user
     *
     * @return void
     */
    public function addUser(Role &$role, EnhancedUser $user);

    /**
     * Add an user to a role
     * This method must insert new user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * Remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role  $role
     * @param int   $userId
     *
     * @return void
     */
    public function addUserById(Role &$role, int $userId);

    /**
     * Add an user to a role
     * This method must insert new user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * Remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role      $role
     * @param string    $userName
     *
     * @return void
     */
    public function addUserByName(Role &$role, string $userName);

    /**
     * Remove user from a role
     * This method must remove user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * As previous method remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role          $role
     * @param EnhancedUser  $user
     *
     * @return void
     */
    public function removeUser(Role &$role, EnhancedUser $user);

    /**
     * Remove user from a role
     * This method must remove user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * As previous method remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role  $role
     * @param int   $userId
     *
     * @return void
     */
    public function removeUserById(Role &$role, int $userId);

    /**
     * Remove user from a role
     * This method must remove user-role coupling in persistent
     * storage and update Role calling Role->setUsers() method.
     * As previous method remind to pass all role's users to Role->setUsers(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param Role      $role
     * @param string    $userName
     *
     * @return void
     */
    public function removeUserByName(Role &$role, string $userName);
}
