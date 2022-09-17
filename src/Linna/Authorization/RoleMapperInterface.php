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

use Linna\Authorization\EnhancedUser;
use Linna\Authorization\Permission;
use Linna\Authorization\Role;
use Linna\DataMapper\MapperInterface;

/**
 * Group Mapper Interface.
 *
 * Contain methods required from concrete Role Mapper.
 */
interface RoleMapperInterface extends MapperInterface, FetchByPermissionInterface, FetchByUserInterface
{
    /**
     * Grant a permission at role.
     *
     * This method must insert new role-permission coupling in persistent
     * storage and update `Role` calling `Role->setPermissions()` method.
     *
     * Remind to pass all role's permission to `Role->setPermissions()`,
     * when write concrete mapper is well to pass `PermissionMapper` as
     * constructor dependency.
     *
     * @param Role       $role       The role class instance.
     * @param Permission $permission The permission to add.
     *
     * @return void
     */
    public function grantPermission(Role &$role, Permission $permission);

    /**
     * Grant a permission at role.
     *
     * This method must insert new role-permission coupling in persistent
     * storage and update `Role` calling `Role->setPermissions()` method.
     *
     * Remind to pass all role's permission to `Role->setPermissions()`,
     * when write concrete mapper is well to pass `PermissionMapper` as
     * constructor dependency.
     *
     * @param Role $role         The role class instance.
     * @param int  $permissionId The permission to add.
     *
     * @return void
     */
    public function grantPermissionById(Role &$role, int $permissionId);

    /**
     * Grant a permission at role.
     *
     * This method must insert new role-permission coupling in persistent
     * storage and update `Role` calling `Role->setPermissions()` method.
     *
     * Remind to pass all role's permission to `Role->setPermissions()`,
     * when write concrete mapper is well to pass `PermissionMapper` as
     * constructor dependency.
     *
     * @param Role   $role           The role class instance.
     * @param string $permissionName The permission to add.
     *
     * @return void
     */
    public function grantPermissionByName(Role &$role, string $permissionName);

    /**
     * Revoke a permission at role.
     *
     * This method must remove role-permission coupling in persistent
     * storage and update `Role` calling `Role->setPermissions()` method.
     *
     * As previous method remind to pass all role's permission
     * to `Role->setPermissions()`, when write concrete mapper is well to
     * pass `PermissionMapper` as constructor dependency.
     *
     * @param Role       $role       The role class instance.
     * @param Permission $permission The permission to revoke.
     *
     * @return void
     */
    public function revokePermission(Role &$role, Permission $permission);

    /**
     * Revoke a permission at role.
     *
     * This method must remove role-permission coupling in persistent
     * storage and update `Role` calling `Role->setPermissions()` method.
     *
     * As previous method remind to pass all role's permission
     * to `Role->setPermissions()`, when write concrete mapper is well to
     * pass `PermissionMapper` as constructor dependency.
     *
     * @param Role $role         The role class instance.
     * @param int  $permissionId The permission to revoke.
     *
     * @return void
     */
    public function revokePermissionById(Role &$role, int $permissionId);

    /**
     * Revoke a permission at role.
     *
     * This method must remove role-permission coupling in persistent
     * storage and update `Role` calling `Role->setPermissions()` method.
     *
     * As previous method remind to pass all role's permission
     * to `Role->setPermissions()`, when write concrete mapper is well to
     * pass `PermissionMapper` as constructor dependency.
     *
     * @param Role   $role           The role class instance.
     * @param string $permissionName The permission to revoke.
     *
     * @return void
     */
    public function revokePermissionByName(Role &$role, string $permissionName);

    /**
     * Add an user to a role.
     *
     * This method must insert new user-role coupling in persistent
     * storage and update `Role` calling `Role->setUsers()` method.
     *
     * Remind to pass all role's users to `Role->setUsers()`,
     * when write concrete mapper is well to pass `EnhancedUserMapper` as
     * constructor dependency.
     *
     * @param Role         $role The role class instance.
     * @param EnhancedUser $user The user to add.
     *
     * @return void
     */
    public function addUser(Role &$role, EnhancedUser $user);

    /**
     * Add an user to a role.
     *
     * This method must insert new user-role coupling in persistent
     * storage and update `Role` calling `Role->setUsers()` method.
     *
     * Remind to pass all role's users to `Role->setUsers()`,
     * when write concrete mapper is well to pass `EnhancedUserMapper` as
     * constructor dependency.
     *
     * @param Role $role   The role class instance.
     * @param int  $userId The user to add.
     *
     * @return void
     */
    public function addUserById(Role &$role, int $userId);

    /**
     * Add an user to a role.
     *
     * This method must insert new user-role coupling in persistent
     * storage and update `Role` calling `Role->setUsers()` method.
     *
     * Remind to pass all role's users to `Role->setUsers()`,
     * when write concrete mapper is well to pass `EnhancedUserMapper` as
     * constructor dependency.
     *
     * @param Role   $role     The role class instance.
     * @param string $userName The user to add.
     *
     * @return void
     */
    public function addUserByName(Role &$role, string $userName);

    /**
     * Remove user from a role.
     *
     * This method must remove user-role coupling in persistent
     * storage and update `Role` calling `Role->setUsers()` method.
     *
     * As previous method remind to pass all role's users to `Role->setUsers()`,
     * when write concrete mapper is well to pass `EnhancedUserMapper` as
     * constructor dependency.
     *
     * @param Role         $role The role class instance.
     * @param EnhancedUser $user The user to revoke.
     *
     * @return void
     */
    public function removeUser(Role &$role, EnhancedUser $user);

    /**
     * Remove user from a role.
     *
     * This method must remove user-role coupling in persistent
     * storage and update `Role` calling `Role->setUsers()` method.
     *
     * As previous method remind to pass all role's users to `Role->setUsers()`,
     * when write concrete mapper is well to pass `EnhancedUserMapper` as
     * constructor dependency.
     *
     * @param Role $role   The role class instance.
     * @param int  $userId The user to revoke.
     *
     * @return void
     */
    public function removeUserById(Role &$role, int $userId);

    /**
     * Remove user from a role.
     *
     * This method must remove user-role coupling in persistent
     * storage and update `Role` calling `Role->setUsers()` method.
     *
     * As previous method remind to pass all role's users to `Role->setUsers()`,
     * when write concrete mapper is well to pass `EnhancedUserMapper` as
     * constructor dependency.
     *
     * @param Role   $role     The role class instance.
     * @param string $userName The user to revoke.
     *
     * @return void
     */
    public function removeUserByName(Role &$role, string $userName);
}
