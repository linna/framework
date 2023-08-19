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
use Linna\DataMapper\FetchByNameInterface;

/**
 * Group Mapper Interface.
 *
 * Contain methods required from concrete Role Mapper.
 */
interface RoleMapperInterface extends MapperInterface, FetchByPermissionInterface, FetchByNameInterface, FetchByUserInterface
{
    /**
     * Grant a permission at role.
     *
     * <p>This method must insert new role-permission coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setPermissions()</code> method.</p>
     *
     * <p>Remind to pass all role's permission to <code>Role->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role       $role       The <code>Role</code> class instance.
     * @param Permission $permission The permission to add as <code>Permission</code> instance.
     *
     * @return void
     */
    public function grantPermission(Role &$role, Permission $permission);

    /**
     * Grant a permission at role.
     *
     * <p>This method must insert new role-permission coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setPermissions()</code> method.</p>
     *
     * <p>Remind to pass all role's permission to <code>Role->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role       $role         The <code>Role</code> class instance.
     * @param int|string $permissionId The permission to add as permission id.
     *
     * @return void
     */
    public function grantPermissionById(Role &$role, int|string $permissionId);

    /**
     * Grant a permission at role.
     *
     * <p>This method must insert new role-permission coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setPermissions()</code> method.</p>
     *
     * <p>Remind to pass all role's permission to <code>Role->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role   $role           The <code>Role</code> class instance.
     * @param string $permissionName The permission to add as permission name.
     *
     * @return void
     */
    public function grantPermissionByName(Role &$role, string $permissionName);

    /**
     * Revoke a permission at role.
     *
     * <p>This method must remove role-permission coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setPermissions()</code> method.</p>
     *
     * <p>As previous method remind to pass all role's permission
     * to <code>Role->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param Role       $role       The <code>Role</code> class instance.
     * @param Permission $permission The permission to revoke as <code>Permission</code> instance.
     *
     * @return void
     */
    public function revokePermission(Role &$role, Permission $permission);

    /**
     * Revoke a permission at role.
     *
     * <p>This method must remove role-permission coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setPermissions()</code> method.</p>
     *
     * <p>As previous method remind to pass all role's permission
     * to <code>Role->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param Role       $role         The <code>Role</code> class instance.
     * @param int|string $permissionId The permission to revoke as permission id.
     *
     * @return void
     */
    public function revokePermissionById(Role &$role, int|string $permissionId);

    /**
     * Revoke a permission at role.
     *
     * <p>This method must remove role-permission coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setPermissions()</code> method.</p>
     *
     * <p>As previous method remind to pass all role's permission
     * to <code>Role->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param Role   $role           The <code>Role</code> class instance.
     * @param string $permissionName The permission to revoke as permission name.
     *
     * @return void
     */
    public function revokePermissionByName(Role &$role, string $permissionName);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setUsers()</code> method.</p>
     *
     * <p>Remind to pass all role's users to <code>Role->setUsers()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role         $role The <code>Role</code> class instance.
     * @param EnhancedUser $user The user to add as <code>EnhancedUser</code> instance.
     *
     * @return void
     */
    public function addUser(Role &$role, EnhancedUser $user);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setUsers()</code> method.</p>
     *
     * <p>Remind to pass all role's users to <code>Role->setUsers()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role       $role   The <code>Role</code> class instance.
     * @param int|string $userId The user to add as user id.
     *
     * @return void
     */
    public function addUserById(Role &$role, int|string $userId);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setUsers()</code> method.</p>
     *
     * <p>Remind to pass all role's users to <code>Role->setUsers()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role   $role     The <code>Role</code> class instance.
     * @param string $userName The user to add as user name.
     *
     * @return void
     */
    public function addUserByName(Role &$role, string $userName);

    /**
     * Remove user from a role.
     *
     * <p>This method must remove user-role coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setUsers()</code> method.</p>
     *
     * <p>As previous method remind to pass all role's users to <code>Role->setUsers()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role         $role The <code>Role</code> class instance.
     * @param EnhancedUser $user The user to revoke as <code>EnhancedUser</code> instance.
     *
     * @return void
     */
    public function removeUser(Role &$role, EnhancedUser $user);

    /**
     * Remove user from a role.
     *
     * <p>This method must remove user-role coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setUsers()</code> method.</p>
     *
     * <p>As previous method remind to pass all role's users to <code>Role->setUsers()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role       $role   The <code>Role</code> class instance.
     * @param int|string $userId The user to revoke as user id.
     *
     * @return void
     */
    public function removeUserById(Role &$role, int|string $userId);

    /**
     * Remove user from a role.
     *
     * <p>This method must remove user-role coupling in persistent
     * storage and update <code>Role</code> calling <code>Role->setUsers()</code> method.</p>
     *
     * <p>As previous method remind to pass all role's users to <code>Role->setUsers()</code>,
     * when write concrete mapper is well to pass <code>EnhancedUserMapper</code> as
     * constructor dependency.</p>
     *
     * @param Role   $role     The <code>Role</code> class instance.
     * @param string $userName The user to revoke as user name.
     *
     * @return void
     */
    public function removeUserByName(Role &$role, string $userName);
}
