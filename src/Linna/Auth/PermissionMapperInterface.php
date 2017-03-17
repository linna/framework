<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

use Linna\DataMapper\MapperInterface;

/**
 * Permission Mapper Interface
 * Contain methods required from concrete Permission Mapper
 */
interface PermissionMapperInterface extends MapperInterface
{
    public function fetchUserPermission(int $userId) : array;
    
    public function permissionExist(string $permission) : bool;
}