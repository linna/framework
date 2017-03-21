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
 * Group Mapper Interface
 * Contain methods required from concrete Group Mapper.
 */
interface RoleMapperInterface extends MapperInterface
{
    public function fetchRolePermissions(int $roleId) : array;
    
    //public function fetchUsersByRole(int $roleId) : array;
    
    public function fetchUserInheritedPermissions(int $roleId) : array;
    
    public function permissionGrant(Role &$role, string $permission);

    public function permissionRevoke(Role &$role, string $permission);
    
    public function userAdd(Role &$role, User $user);

    public function userRemove(Role &$role, User $user);
    
}
