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

use Linna\DataMapper\FetchByNameInterface;
use Linna\DataMapper\MapperInterface;

/**
 * Permission Mapper Interface
 * Contain methods required from concrete Permission Mapper.
 */
interface PermissionMapperInterface extends MapperInterface, FetchByNameInterface, FetchByUserInterface, FetchByRoleInterface
{
    /**
     * Check if a permission exist.
     *
     * @param int $permissionId
     *
     * @return bool
     */
    public function permissionExistById(int $permissionId): bool;

    /**
     * Check if a permission exist.
     *
     * @param string $permissionName
     *
     * @return bool
     */
    public function permissionExistByName(string $permissionName): bool;
}
