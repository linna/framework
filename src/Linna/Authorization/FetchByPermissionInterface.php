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
     * From a Permission instance as argument, this method must return an array
     * containing a `EnhancedUser|Role` instance for every `EnhancedUser|Role`
     * that have the given permission.
     *
     * @param Permission $permission The permission which will be used to fetch.
     *
     * @return array<mixed> Users or roles which the permission is granted.
     */
    public function fetchByPermission(Permission $permission): array;

    /**
     * Fetch from permission.
     *
     * From a permission id as argument, this method must return an array
     * containing a `EnhancedUser|Role` instance for every `EnhancedUser|Role`
     * that have the given permission.
     *
     * @param int $permissionId The permission which will be used to fetch.
     *
     * @return array<mixed> Users or roles which the permission is granted.
     */
    public function fetchByPermissionId(int $permissionId): array;

    /**
     * Fetch from permission.
     *
     * From a permission name as argument, this method must return an array
     * containing a `EnhancedUser|Role` instance for every `EnhancedUser|Role`
     * that have the given permission.
     *
     * @param string $permissionName The permission which will be used to fetch.
     *
     * @return array<mixed> Users or roles which the permission is granted.
     */
    public function fetchByPermissionName(string $permissionName): array;
}
