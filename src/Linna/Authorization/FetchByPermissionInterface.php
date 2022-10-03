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

use Linna\Authorization\Permission;

/**
 * Fetch By Permission Interface.
 *
 * Contain methods required to fetch users or roles by permission.
 */
interface FetchByPermissionInterface
{
    /**
     * Fetch from permission.
     *
     * <p>From a <code>Permission</code> instance as argument, this method must return an array
     * containing a <code>EnhancedUser|Role</code> instance for every <code>EnhancedUser|Role</code>
     * that have the given permission.</p>
     *
     * @param Permission $permission The permission which will be used to fetch as <code>Permission</code> instance.
     *
     * @return array<mixed> Users or roles which the permission is granted.
     */
    public function fetchByPermission(Permission $permission): array;

    /**
     * Fetch from permission.
     *
     * <p>From a permission id as argument, this method must return an array
     * containing a <code>EnhancedUser|Role</code> instance for every <code>EnhancedUser|Role</code>
     * that have the given permission.</p>
     *
     * @param int|string $permissionId The permission which will be used to fetch as permission id.
     *
     * @return array<mixed> Users or roles which the permission is granted.
     */
    public function fetchByPermissionId(int|string $permissionId): array;

    /**
     * Fetch from permission.
     *
     * <p>From a permission name as argument, this method must return an array
     * containing a <code>EnhancedUser|Role</code> instance for every <code>EnhancedUser|Role</code>
     * that have the given permission.</p>
     *
     * @param string $permissionName The permission which will be used to fetch as permission name.
     *
     * @return array<mixed> Users or roles which the permission is granted.
     */
    public function fetchByPermissionName(string $permissionName): array;
}
