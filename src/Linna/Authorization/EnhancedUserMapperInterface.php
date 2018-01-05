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

use Linna\DataMapper\MapperInterface;

/**
 * User Mapper Interface
 * Contain methods required from concrete User Mapper.
 */
interface EnhancedUserMapperInterface extends MapperInterface
{
    /**
     * Fetch users by role.<br/>
     * From a role id as argument, this method must return an array containing
     * a <code class="php_i">User</code> object instance for every user that belongs at the
     * given role.
     *
     * @param int $roleId
     *
     * @return array
     */
    public function fetchUserByRole(int $roleId) : array;

    /**
     * Fetch users by permission.<br/>
     * From a permission id as argument, this method must return an array containing
     * a <code class="php_i">User</code> object instance for every user that have the
     * given permission.
     *
     * @param int $permissionId
     *
     * @return array
     */
    public function fetchUserByPermission(int $permissionId) : array;

    /**
     * Grant a permission to an user.<br/>
     * This method must insert new user-permission coupling in persistent
     * storage and update <code class="php_i">EnhancedUser</code> calling
     * <code class="php_i">EnhancedUser->setPermissions()</code> method.<br/>
     * Remind to pass all user's permission to
     * <code class="php_i">EnhancedUser->setPermissions()</code>,
     * when write concrete mapper is well pass PermissionMapper as constructor
     * dependency.
     *
     * @param EnhancedUser $user
     * @param string       $permission
     */
    public function grant(EnhancedUser &$user, string $permission);

    /**
     * Revoke a permission to an user.<br/>
     * This method must remove user-permission coupling in persistent
     * storage and update <code class="php_i">EnhancedUser</code> calling
     * <code class="php_i">EnhancedUser->setPermissions()</code> method.<br/>
     * As previous method remind to pass all user's permission to
     * <code class="php_i">EnhancedUser->setPermissions()</code>, when write concrete mapper is
     * well pass PermissionMapper as constructor dependency.
     *
     * @param EnhancedUser $user
     * @param string       $permission
     */
    public function revoke(EnhancedUser &$user, string $permission);
}
