<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

/**
 * User PermissionTrait.
 */
trait PermissionTrait
{
    /**
     * @var array<mixed> User permissions
     */
    protected array $permission = [];

    /**
     * Check if a Permission is owned, use Permission instance.
     *
     * @param Permission $permission
     *
     * @return bool
     */
    public function can(Permission $permission): bool
    {
        return $this->canById($permission->getId());
    }

    /**
     * Check if a Permission is owned, use permission Id.
     *
     * @param int $permissionId
     *
     * @return bool
     */
    public function canById(int $permissionId): bool
    {
        if (isset($this->permission[$permissionId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if a Permission is owned, use permission name.
     *
     * @param string $permissionName
     *
     * @return bool
     */
    public function canByName(string $permissionName): bool
    {
        if (\in_array($permissionName, \array_column($this->permission, 'name'), true)) {
            return true;
        }

        return false;
    }
}
