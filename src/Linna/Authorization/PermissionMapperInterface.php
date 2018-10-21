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
     * Combine al users-roles-permissions in storage for
     * passed user id.
     * Return an array containing a sha256 hash of
     * user_id.permission_id string.
     *
     * Example
     * Hash will be match with below code sample
     *      <?php
     *      $hash = hash('sha256', $userId.'.'.$permissionId);
     *
     * @param int $userId
     *
     * @return array
     */
    public function fetchUserPermissionHashTable(int $userId): array;

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
