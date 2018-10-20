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

use Linna\Authorization\Permission;

/**
 * Fetch By Permission Interface
 * Contain methods required from fetching by Permission.
 */
interface FetchByPermissionInterface
{
    /**
     * Fetch from permission.
     * From a Permission instance as argument, this method must return an array
     * containing a EnhancedUser|Role instance for every EnhancedUser|Role
     * that have the given permission.
     *
     * @param Permission $permission
     *
     * @return array
     */
    public function fetchByPermission(Permission $permission): array;

    /**
     * Fetch from permission.
     * From a permission id as argument, this method must return an array
     * containing a EnhancedUser|Role instance for every EnhancedUser|Role
     * that have the given permission.
     *
     * @param int $permissionId
     *
     * @return array
     */
    public function fetchByPermissionId(int $permissionId): array;

    /**
     * Fetch from permission.
     * From a permission name as argument, this method must return an array
     * containing a EnhancedUser|Role instance for every EnhancedUser|Role
     * that have the given permission.
     *
     * @param string $permissionName
     *
     * @return array
     */
    public function fetchByPermissionName(string $permissionName): array;
}
