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
use Linna\DataMapper\DomainObjectInterface;

/**
 * Permission Mapper Interface
 * Contain methods required from concrete Permission Mapper.
 */
interface PermissionMapperInterface extends MapperInterface
{
    public function fetchByName(string $permissionName) : DomainObjectInterface;
    
    public function fetchPermissionsByRole(int $roleId) : array;
    
    public function fetchPermissionsByUser(int $userId) : array;

    public function generatePermissionHashTable() : array;
    
    public function permissionExist(string $permission) : bool;
}
