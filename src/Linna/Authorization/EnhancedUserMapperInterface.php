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
 * User Mapper Interface
 * Contain methods required from concrete User Mapper.
 */
interface EnhancedUserMapperInterface extends MapperInterface, FetchByPermissionInterface, FetchByRoleInterface
{
    /**
     * Grant a permission to an user.
     * This method must insert new user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions() method.<br/>
     * Remind to pass all user's permission to EnhancedUser->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param Permission    $permission
     *
     * @return void
     */
    public function grantPermission(EnhancedUser &$user, Permission $permission);

    /**
     * Grant a permission to an user.
     * This method must insert new user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions() method.<br/>
     * Remind to pass all user's permission to EnhancedUser->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param int           $permissionId
     *
     * @return void
     */
    public function grantPermissionById(EnhancedUser &$user, int $permissionId);

    /**
     * Grant a permission to an user.
     * This method must insert new user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions() method.<br/>
     * Remind to pass all user's permission to EnhancedUser->setPermissions(),
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param string        $permissionName
     *
     * @return void
     */
    public function grantPermissionByName(EnhancedUser &$user, string $permissionName);

    /**
     * Revoke a permission to an user.
     * This method must remove user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions() method.<br/>
     * As previous method remind to pass all user's permission to
     * EnhancedUser->setPermissions(), when write concrete mapper is
     * well pass PermissionMapper as constructor dependency.
     *
     * @param EnhancedUser $user
     * @param Permission   $permission
     * 
     * @return void
     */
    public function revokePermission(EnhancedUser &$user, Permission $permission);

    /**
     * Revoke a permission to an user.
     * This method must remove user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions() method.
     * As previous method remind to pass all user's permission to
     * EnhancedUser->setPermissions(), when write concrete mapper is
     * well pass PermissionMapper as constructor dependency.
     *
     * @param EnhancedUser  $user
     * @param int           $permissionId
     *
     * @return void
     */
    public function revokePermissionById(EnhancedUser &$user, int $permissionId);

    /**
     * Revoke a permission to an user.
     * This method must remove user-permission coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setPermissions() method.
     * As previous method remind to pass all user's permission to
     * EnhancedUser->setPermissions(), when write concrete mapper is
     * well pass PermissionMapper as constructor dependency.
     *
     * @param EnhancedUser  $user
     * @param string        $permissionName
     *
     * @return void
     */
    public function revokePermissionByName(EnhancedUser &$user, string $permissionName);

    /**
     * Add an user to a role
     * This method must insert new user-role coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setRoles() method.
     * Remind to pass all role's users to EnhancedUser->setRoles(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param Role          $role
     *
     * @return void
     */
    public function addRole(EnhancedUser &$user, Role $role);

    /**
     * Add an user to a role
     * This method must insert new user-role coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setRoles() method.
     * Remind to pass all role's users to EnhancedUser->setRoles(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param int           $roleId
     *
     * @return void
     */
    public function addRoleById(EnhancedUser &$user, int $roleId);

    /**
     * Add an user to a role
     * This method must insert new user-role coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setRoles() method.
     * Remind to pass all role's users to EnhancedUser->setRoles(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param string        $roleName
     *
     * @return void
     */
    public function addRoleByName(EnhancedUser &$user, string $roleName);

    /**
     * Remove user from a role
     * This method must insert new user-role coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setRoles() method.
     * Remind to pass all role's users to EnhancedUser->setRoles(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param Role          $role
     *
     * @return void
     */
    public function removeRole(EnhancedUser &$user, Role $role);

    /**
     * Remove user from a role
     * This method must insert new user-role coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setRoles() method.
     * Remind to pass all role's users to EnhancedUser->setRoles(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param int           $roleId
     *
     * @return void
     */
    public function removeRoleById(EnhancedUser &$user, int $roleId);

    /**
     * Remove user from a role
     * This method must insert new user-role coupling in persistent
     * storage and update EnhancedUser calling EnhancedUser->setRoles() method.
     * Remind to pass all role's users to EnhancedUser->setRoles(),
     * when write concrete mapper is well pass EnhancedUserMapper as constructor
     * dependency.
     *
     * @param EnhancedUser  $user
     * @param string        $roleName
     *
     * @return void
     */
    public function removeRoleByName(EnhancedUser &$user, string $roleName);
}
