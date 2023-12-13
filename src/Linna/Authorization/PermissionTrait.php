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

/**
 * Permission Trait.
 *
 * <p>Use it to add the permission functionality to a class.</p>
 */
trait PermissionTrait
{
    /** @var array<mixed> Permissions. */
    protected array $permission = [];

    /**
     * Check if the class that use the trait has the permission, use Permission
     * instance.
     *
     * @param Permission $permission The permission which will be checked as <code>Permission</code> instance.
     *
     * @return bool True if the permission is granted, false otherwise.
     */
    public function can(Permission $permission): bool
    {
        return $this->canById($permission->getId());
    }

    /**
     * Check if the class that use the trait has the permission, use permission
     * id.
     *
     * @param null|int|string $permissionId The permission which will be checked as permission id.
     *
     * @return bool True if the permission is granted, false otherwise.
     */
    public function canById(null|int|string $permissionId): bool
    {
        //if (isset($this->permission[$permissionId])) {
        //    return true;
        //}

        if (\in_array($permissionId, \array_column($this->permission, 'id'), true)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the class that use the trait has the permission, use permission
     * name.
     *
     * @param string $permissionName The permission which will be checked as permission name.
     *
     * @return bool True if the permission is granted, false otherwise.
     */
    public function canByName(string $permissionName): bool
    {
        //if (isset($this->permission[$permissionName])) {
        //    return true;
        //}

        if (\in_array($permissionName, \array_column($this->permission, 'name'), true)) {
            return true;
        }

        return false;
    }
}
