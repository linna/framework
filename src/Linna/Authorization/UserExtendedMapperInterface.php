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

use Linna\Authorization\UserExtended;
use Linna\Authorization\Permission;
use Linna\Authorization\Role;
use Linna\DataMapper\MapperInterface;
use Linna\DataMapper\FetchByNameInterface;

/**
 * User Mapper Interface.
 *
 * Contains methods required from concrete User Mapper.
 */
interface UserExtendedMapperInterface extends MapperInterface, FetchByNameInterface, FetchByPermissionInterface, FetchByRoleInterface
{
    /**
     * Grant a permission to an user.
     *
     * <p>This method must insert new user-permission coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setPermissions()</code>
     * method.</p>
     *
     * <p>Remind to pass all user's permission to <code>UserExtended->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param UserExtended $user       The <code>UserExtended</code> user class instance.
     * @param Permission   $permission The permission to add as <code>Permission</code> class instance.
     *
     * @return void
     */
    public function grantPermission(UserExtended &$user, Permission $permission);

    /**
     * Grant a permission to an user.
     *
     * <p>This method must insert new user-permission coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setPermissions()</code>
     * method.</p>
     *
     * <p>Remind to pass all user's permission to <code>UserExtended->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param UserExtended $user         The <code>UserExtended</code> user class instance.
     * @param int|string   $permissionId The permission to add as persmission id.
     *
     * @return void
     */
    public function grantPermissionById(UserExtended &$user, int|string $permissionId);

    /**
     * Grant a permission to an user.
     *
     * <p>This method must insert new user-permission coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setPermissions()</code>
     * method.</p>
     *
     * <p>Remind to pass all user's permission to <code>UserExtended->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param UserExtended $user           The <code>UserExtended</code> user class instance.
     * @param string       $permissionName The permission to add as persmission name.
     *
     * @return void
     */
    public function grantPermissionByName(UserExtended &$user, string $permissionName);

    /**
     * Revoke a permission to an user.
     *
     * <p>This method must remove user-permission coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setPermissions()</code>
     * method.</p>
     *
     * <p>As previous method remind to pass all user's permission to
     * <code>UserExtended->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param UserExtended $user       The <code>UserExtended</code> user class instance.
     * @param Permission   $permission The permission to revoke as <code>Permission</code> class instance..
     *
     * @return void
     */
    public function revokePermission(UserExtended &$user, Permission $permission);

    /**
     * Revoke a permission to an user.
     *
     * <p>This method must remove user-permission coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setPermissions()</code>
     * method.</p>
     *
     * <p>As previous method remind to pass all user's permission to
     * <code>UserExtended->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param UserExtended $user         The <code>UserExtended</code> user class instance.
     * @param int|string   $permissionId The permission to revoke as persmission id.
     *
     * @return void
     */
    public function revokePermissionById(UserExtended &$user, int|string $permissionId);

    /**
     * Revoke a permission to an user.
     *
     * <p>This method must remove user-permission coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setPermissions()</code>
     * method.</p>
     *
     * <p>As previous method remind to pass all user's permission to
     * <code>UserExtended->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param UserExtended $user           The <code>UserExtended</code> user class instance.
     * @param string       $permissionName The permission to revoke as permission name.
     *
     * @return void
     */
    public function revokePermissionByName(UserExtended &$user, string $permissionName);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>UserExtended->setRoles()</code>, when write
     * concrete mapper is well to pass <code>UserExtendedMapper</code> as constructor
     * dependency.</p>
     *
     * @param UserExtended $user The <code>UserExtended</code> user class instance.
     * @param Role         $role The role to add as <code>Role</code> class instance.
     *
     * @return void
     */
    public function addRole(UserExtended &$user, Role $role);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>UserExtended->setRoles()</code>, when write
     * concrete mapper is well to pass <code>UserExtendedMapper</code> as constructor
     * dependency.</p>
     *
     * @param UserExtended $user   The <code>UserExtended</code> user class instance.
     * @param int|string   $roleId The role to add as role id.
     *
     * @return void
     */
    public function addRoleById(UserExtended &$user, int|string $roleId);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>UserExtended->setRoles()</code>, when write
     * concrete mapper is well to pass <code>UserExtendedMapper</code> as constructor
     * dependency.</p>
     *
     * @param UserExtended $user     The <code>UserExtended</code> user class instance.
     * @param string       $roleName The role to add as role name.
     *
     * @return void
     */
    public function addRoleByName(UserExtended &$user, string $roleName);

    /**
     * Remove an user from a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>UserExtended->setRoles()</code>,
     * when write concrete mapper is well to pass <code>UserExtendedMapper</code> as
     * constructor dependency.</p>
     *
     * @param UserExtended $user The <code>UserExtended</code> user class instance.
     * @param Role         $role The role to revoke as <code>Role</code> class instance.
     *
     * @return void
     */
    public function removeRole(UserExtended &$user, Role $role);

    /**
     * Remove an user from a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setRoles()</code> method.</p>
     *
     * <p>Remind to pass all role's users to <code>UserExtended->setRoles()</code>,
     * when write concrete mapper is well to pass <code>UserExtendedMapper</code> as
     * constructor dependency.</p>
     *
     * @param UserExtended $user   The <code>UserExtended</code> user class instance.
     * @param int|string   $roleId The role to revoke as role id.
     *
     * @return void
     */
    public function removeRoleById(UserExtended &$user, int|string $roleId);

    /**
     * Remove an user from a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>UserExtended</code> calling <code>UserExtended->setRoles()</code>
     * method.</p>
     *
     * <p>Remind to pass all role's users to <code>UserExtended->setRoles()</code>,
     * when write concrete mapper is well to pass <code>UserExtendedMapper</code> as
     * constructor dependency.</p>
     *
     * @param UserExtended $user     The <code>UserExtended</code> user class instance.
     * @param string       $roleName The role to revoke as role name.
     *
     * @return void
     */
    public function removeRoleByName(UserExtended &$user, string $roleName);
}
