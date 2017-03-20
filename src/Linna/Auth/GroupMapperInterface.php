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
interface GroupMapperInterface extends MapperInterface
{
    public function fetchGroupPermissions(int $groupId) : array;
    
    public function fetchUsersInGroup(int $groupId) : array;
    
    public function fetchUserInheritedPermissions(int $userId) : array;
    
    public function permissionGrant(Group &$group, string $permission);

    public function permissionRevoke(Group &$group, string $permission);
    
    public function userAdd(Group &$group, User $user);

    public function userRemove(Group &$group, User $user);
    
}
