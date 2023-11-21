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

use Linna\Authorization\User;
use Linna\Authorization\Permission;
use Linna\Authorization\RoleExtendedExtended;
use Linna\DataMapper\MapperInterface;
use Linna\DataMapper\FetchByNameInterface;

/**
 * Role Extended Mapper Interface.
 *
 * Contain methods required from concrete RoleExtended Mapper.
 */
interface RoleExtendedMapperInterface extends  MapperInterface, FetchByNameInterface, FetchByPermissionInterface, FetchByUserInterface
{
    /**
     * Grant a permission at role.
     *
     * <p>This method must insert new role-permission coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setPermissions()</code> method.</p>
     *
     * <p>Remind to pass all RoleExtended's permission to <code>RoleExtended->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role       The <code>RoleExtended</code> class instance.
     * @param Permission   $permission The permission to add as <code>Permission</code> instance.
     *
     * @return void
     */
    public function grantPermission(RoleExtended &$role, Permission $permission);

    /**
     * Grant a permission at role.
     *
     * <p>This method must insert new role-permission coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setPermissions()</code> method.</p>
     *
     * <p>Remind to pass all RoleExtended's permission to <code>RoleExtended->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role         The <code>RoleExtended</code> class instance.
     * @param int|string   $permissionId The permission to add as permission id.
     *
     * @return void
     */
    public function grantPermissionById(RoleExtended &$role, int|string $permissionId);

    /**
     * Grant a permission at role.
     *
     * <p>This method must insert new role-permission coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setPermissions()</code> method.</p>
     *
     * <p>Remind to pass all RoleExtended's permission to <code>RoleExtended->setPermissions()</code>,
     * when write concrete mapper is well to pass <code>PermissionMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role           The <code>RoleExtended</code> class instance.
     * @param string       $permissionName The permission to add as permission name.
     *
     * @return void
     */
    public function grantPermissionByName(RoleExtended &$role, string $permissionName);

    /**
     * Revoke a permission at role.
     *
     * <p>This method must remove role-permission coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setPermissions()</code> method.</p>
     *
     * <p>As previous method remind to pass all RoleExtended's permission
     * to <code>RoleExtended->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param RoleExtended $role       The <code>RoleExtended</code> class instance.
     * @param Permission   $permission The permission to revoke as <code>Permission</code> instance.
     *
     * @return void
     */
    public function revokePermission(RoleExtended &$role, Permission $permission);

    /**
     * Revoke a permission at role.
     *
     * <p>This method must remove role-permission coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setPermissions()</code> method.</p>
     *
     * <p>As previous method remind to pass all RoleExtended's permission
     * to <code>RoleExtended->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param RoleExtended $role         The <code>RoleExtended</code> class instance.
     * @param int|string   $permissionId The permission to revoke as permission id.
     *
     * @return void
     */
    public function revokePermissionById(RoleExtended &$role, int|string $permissionId);

    /**
     * Revoke a permission at role.
     *
     * <p>This method must remove role-permission coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setPermissions()</code> method.</p>
     *
     * <p>As previous method remind to pass all RoleExtended's permission
     * to <code>RoleExtended->setPermissions()</code>, when write concrete mapper is well to
     * pass <code>PermissionMapper</code> as constructor dependency.</p>
     *
     * @param RoleExtended $role           The <code>RoleExtended</code> class instance.
     * @param string       $permissionName The permission to revoke as permission name.
     *
     * @return void
     */
    public function revokePermissionByName(RoleExtended &$role, string $permissionName);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setUsers()</code> method.</p>
     *
     * <p>Remind to pass all RoleExtended's users to <code>RoleExtended->setUsers()</code>,
     * when write concrete mapper is well to pass <code>UserMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role The <code>RoleExtended</code> class instance.
     * @param User         $user The user to add as <code>User</code> instance.
     *
     * @return void
     */
    public function addUser(RoleExtended &$role, User $user);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setUsers()</code> method.</p>
     *
     * <p>Remind to pass all RoleExtended's users to <code>RoleExtended->setUsers()</code>,
     * when write concrete mapper is well to pass <code>UserMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role   The <code>RoleExtended</code> class instance.
     * @param int|string   $userId The user to add as user id.
     *
     * @return void
     */
    public function addUserById(RoleExtended &$role, int|string $userId);

    /**
     * Add an user to a role.
     *
     * <p>This method must insert new user-role coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setUsers()</code> method.</p>
     *
     * <p>Remind to pass all RoleExtended's users to <code>RoleExtended->setUsers()</code>,
     * when write concrete mapper is well to pass <code>UserMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role     The <code>RoleExtended</code> class instance.
     * @param string       $userName The user to add as user name.
     *
     * @return void
     */
    public function addUserByName(RoleExtended &$role, string $userName);

    /**
     * Remove user from a role.
     *
     * <p>This method must remove user-role coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setUsers()</code> method.</p>
     *
     * <p>As previous method remind to pass all RoleExtended's users to <code>RoleExtended->setUsers()</code>,
     * when write concrete mapper is well to pass <code>UserMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role The <code>RoleExtended</code> class instance.
     * @param User         $user The user to revoke as <code>User</code> instance.
     *
     * @return void
     */
    public function removeUser(RoleExtended &$role, User $user);

    /**
     * Remove user from a role.
     *
     * <p>This method must remove user-role coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setUsers()</code> method.</p>
     *
     * <p>As previous method remind to pass all RoleExtended's users to <code>RoleExtended->setUsers()</code>,
     * when write concrete mapper is well to pass <code>UserMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role   The <code>RoleExtended</code> class instance.
     * @param int|string   $userId The user to revoke as user id.
     *
     * @return void
     */
    public function removeUserById(RoleExtended &$role, int|string $userId);

    /**
     * Remove user from a role.
     *
     * <p>This method must remove user-role coupling in persistent
     * storage and update <code>RoleExtended</code> calling <code>RoleExtended->setUsers()</code> method.</p>
     *
     * <p>As previous method remind to pass all RoleExtended's users to <code>RoleExtended->setUsers()</code>,
     * when write concrete mapper is well to pass <code>UserMapper</code> as
     * constructor dependency.</p>
     *
     * @param RoleExtended $role     The <code>RoleExtended</code> class instance.
     * @param string       $userName The user to revoke as user name.
     *
     * @return void
     */
    public function removeUserByName(RoleExtended &$role, string $userName);
}
