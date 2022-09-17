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
 * Contain methods required from concrete Permission Mapper.
 */
interface PermissionMapperInterface extends MapperInterface, FetchByNameInterface, FetchByUserInterface, FetchByRoleInterface
{
    /**
     * Check if a permission exist.
     *
     * @param int $permissionId The permission will be checked.
     *
     * @return bool True if the permission exists, false otherwise.
     */
    public function permissionExistById(int $permissionId): bool;

    /**
     * Check if a permission exist.
     *
     * @param string $permissionName The permission will be checked.
     *
     * @return bool True if the permission exists, false otherwise.
     */
    public function permissionExistByName(string $permissionName): bool;
}
