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

/**
 * User PermissionTrait
 */
trait UserPermissionTrait
{
    /**
     * @var array User permissions
     */
    protected $permission;
    
    /**
     * 
     * @param array $permissions
     */
    public function setPermissions(array $permissions)
    {
        $this->permission = $permissions;
    }
    
    public function can(string $permission) : bool
    {
        $userPermissions = $this->permission;
        
        foreach ($userPermissions as $uPermission)
        {
            if ($uPermission->name === $permission)
            {
                return true;
            }
        }
        
        return false;
    }
}
