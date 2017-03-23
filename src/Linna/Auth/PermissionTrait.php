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
     * Show Permissions.
     *
     * @return array
     */
    public function showPermissions() : array
    {
        $arrayPermissions = [];

        foreach ($this->permission as $ownPermission) {
            $arrayPermissions[] = $ownPermission->name;
        }

        return $arrayPermissions;
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
