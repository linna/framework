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

/**
 * User PermissionTrait.
 */
trait PermissionTrait
{
    /**
     * @var array User permissions
     */
    protected $permission;

    /**
     * Set permission.
     *
     * @param array $permissions
     */
    public function setPermissions(array $permissions)
    {
        $this->permission = $permissions;
    }

    /**
     * Get Permissions.
     *
     * @return array
     */
    public function getPermissions() : array
    {
        return $this->permission;
    }

    /**
     * Check Permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can(string $permission) : bool
    {
        foreach ($this->permission as $ownPermission) {
            if ($ownPermission->name === $permission) {
                return true;
            }
        }

        return false;
    }
}
