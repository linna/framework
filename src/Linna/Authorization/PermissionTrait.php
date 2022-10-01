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

/**
 * User PermissionTrait.
 *
 * <p>Use it to add the permission functionality to a class.</p>
 */
trait PermissionTrait
{
    /** @var array<mixed> User permissions. */
    protected array $permission = [];

    /**
     * Check if a permission is granted to a user or to a role, use <code>Permission</code>
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
     * Check if a Permission is granted to a user or to a role, use permission
     * id.
     *
     * @param string|int $permissionId The permission which will be checked as permission id.
     *
     * @return bool True if the permission is granted, false otherwise.
     */
    public function canById(string|int $permissionId): bool
    {
        if (isset($this->permission[$permissionId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if a Permission is granted to a user or to a role, use permission
     * name.
     *
     * @param string $permissionName The permission which will be checked as permission name.
     *
     * @return bool True if the permission is granted, false otherwise.
     */
    public function canByName(string $permissionName): bool
    {
        if (\in_array($permissionName, \array_column($this->permission, 'name'), true)) {
            return true;
        }

        return false;
    }
}
