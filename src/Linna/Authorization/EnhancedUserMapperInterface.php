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

use Linna\Authorization\EnhancedUser;
use Linna\Authorization\Permission;
use Linna\Authorization\Role;
use Linna\DataMapper\MapperInterface;

/**
 * User Mapper Interface.
 *
 * Contains methods required from concrete User Mapper.
 */
interface EnhancedUserMapperInterface extends MapperInterface, FetchByPermissionInterface, FetchByRoleInterface
{
    /**
     * Grant a permission to an user.
     *
     * <p>This method must insert new user-permission coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setPermissions()</code>
     * method.</p>
     *
     * <p>Remind to pass all user's permission to <code>EnhancedUser->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param EnhancedUser $user       The <code>EnhancedUser</code> user class instance.
     * @param Permission   $permission The permission to add as <code>Permission</code> class instance.
     *
     * @return void
     */
    public function grantPermission(EnhancedUser &$user, Permission $permission);

    /**
     * Grant a permission to an user.
     *
     * <p>This method must insert new user-permission coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setPermissions()</code>
     * method.</p>
     *
     * <p>Remind to pass all user's permission to <code>EnhancedUser->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param EnhancedUser $user         The <code>EnhancedUser</code> user class instance.
     * @param int|string   $permissionId The permission to add as persmission id.
     *
     * @return void
     */
    public function grantPermissionById(EnhancedUser &$user, int|string $permissionId);

    /**
     * Grant a permission to an user.
     *
     * <p>This method must insert new user-permission coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setPermissions()</code>
     * method.</p>
     *
     * <p>Remind to pass all user's permission to <code>EnhancedUser->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param EnhancedUser $user           The <code>EnhancedUser</code> user class instance.
     * @param string       $permissionName The permission to add as persmission name.
     *
     * @return void
     */
    public function grantPermissionByName(EnhancedUser &$user, string $permissionName);

    /**
     * Revoke a permission to an user.
     *
     * <p>This method must remove user-permission coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setPermissions()</code>
     * method.</p>
     *
     * <p>As previous method remind to pass all user's permission to
     * <code>EnhancedUser->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param EnhancedUser $user       The <code>EnhancedUser</code> user class instance.
     * @param Permission   $permission The permission to revoke as <code>Permission</code> class instance..
     *
     * @return void
     */
    public function revokePermission(EnhancedUser &$user, Permission $permission);

    /**
     * Revoke a permission to an user.
     *
     * <p>This method must remove user-permission coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setPermissions()</code>
     * method.</p>
     *
     * <p>As previous method remind to pass all user's permission to
     * <code>EnhancedUser->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param EnhancedUser $user         The <code>EnhancedUser</code> user class instance.
     * @param int|string   $permissionId The permission to revoke as persmission id.
     *
     * @return void
     */
    public function revokePermissionById(EnhancedUser &$user, int|string $permissionId);

    /**
     * Revoke a permission to an user.
     *
     * <p>This method must remove user-permission coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setPermissions()</code>
     * method.</p>
     *
     * <p>As previous method remind to pass all user's permission to
     * <code>EnhancedUser->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param EnhancedUser $user           The <code>EnhancedUser</code> user class instance.
     * @param string       $permissionName The permission to revoke as permission name.
     *
     * @return void
     */
    public function revokePermissionByName(EnhancedUser &$user, string $permissionName);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>EnhancedUser->setRoles()</code>, when write
     * concrete mapper is well to pass <code>EnhancedUserMapper</code> as constructor
     * dependency.</p>
     *
     * @param EnhancedUser $user The <code>EnhancedUser</code> user class instance.
     * @param Role         $role The role to add as <code>Role</code> class instance.
     *
     * @return void
     */
    public function addRole(EnhancedUser &$user, Role $role);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>EnhancedUser->setRoles()</code>, when write
     * concrete mapper is well to pass <code>EnhancedUserMapper</code> as constructor
     * dependency.</p>
     *
     * @param EnhancedUser $user   The <code>EnhancedUser</code> user class instance.
     * @param int|string   $roleId The role to add as role id.
     *
     * @return void
     */
    public function addRoleById(EnhancedUser &$user, int|string $roleId);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>EnhancedUser->setRoles()</code>, when write
     * concrete mapper is well to pass <code>EnhancedUserMapper</code> as constructor
     * dependency.</p>
     *
     * @param EnhancedUser $user     The <code>EnhancedUser</code> user class instance.
     * @param string       $roleName The role to add as role name.
     *
     * @return void
     */
    public function addRoleByName(EnhancedUser &$user, string $roleName);

    /**
     * Remove an user from a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>EnhancedUser->setRoles()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param EnhancedUser $user The <code>EnhancedUser</code> user class instance.
     * @param Role         $role The role to revoke as <code>Role</code> class instance.
     *
     * @return void
     */
    public function removeRole(EnhancedUser &$user, Role $role);

    /**
     * Remove an user from a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setRoles()</code> method.</p>
     *
     * <p>Remind to pass all role's users to <code>EnhancedUser->setRoles()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param EnhancedUser $user   The <code>EnhancedUser</code> user class instance.
     * @param int|string   $roleId The role to revoke as role id.
     *
     * @return void
     */
    public function removeRoleById(EnhancedUser &$user, int|string $roleId);

    /**
     * Remove an user from a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>EnhancedUser</code> calling <code>EnhancedUser->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>EnhancedUser->setRoles()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param EnhancedUser $user     The <code>EnhancedUser</code> user class instance.
     * @param string       $roleName The role to revoke as role name.
     *
     * @return void
     */
    public function removeRoleByName(EnhancedUser &$user, string $roleName);
}
