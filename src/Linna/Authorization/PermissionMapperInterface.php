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

use Linna\DataMapper\FetchByNameInterface;
use Linna\DataMapper\MapperInterface;

/**
 * Permission Mapper Interface.
 *
 * <p>Contain methods required from concrete permission mapper.</p>
 */
interface PermissionMapperInterface extends
    MapperInterface,
    FetchByNameInterface,
    FetchByUserInterface,
    FetchByRoleInterface
{
    /**
     * Check if a permission exist.
     *
     * @param int|string $permissionId The permission will be checked as permission id.
     *
     * @return bool True if the permission exists, false otherwise.
     */
    public function permissionExistById(int|string $permissionId): bool;

    /**
     * Check if a permission exist.
     *
     * @param string $permissionName The permission will be checked as permission name.
     *
     * @return bool True if the permission exists, false otherwise.
     */
    public function permissionExistByName(string $permissionName): bool;
}
