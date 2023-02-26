<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use Linna\Authentication\Authentication;
use Linna\Authorization\Permission;

/**
 * Provide methods to check permissions for authenticated user.
 */
class Authorization
{
    use PermissionTrait {
        PermissionTrait::can as canByObject;
    }

    /** @var int|string User id or uuid */
    protected int|string $userId = 0;

    /**
     * Class Constructor.
     *
     * @param Authentication            $authentication   The <code>Authentication</code> instance.
     * @param PermissionMapperInterface $permissionMapper A <code>PermissionMapperInterface</code> implementation.
     */
    public function __construct(Authentication $authentication, PermissionMapperInterface $permissionMapper)
    {
        $userId = $authentication->getLoginData()['user_id'];

        $this->userId = empty($userId) ? 0 : $userId;
        $this->permission = $permissionMapper->fetchByUserId($this->userId);
    }

    /**
     * Check if authenticated user has a permission/can do an action.
     *
     * @param mixed $permission The permission which will be checked.
     *
     * @return bool True if the uses has the permission, false otherwise.
     */
    public function can(mixed $permission): bool
    {
        if ($permission instanceof Permission) {
            return $this->canByObject($permission);
        }

        if (\is_int($permission)) {
            return $this->canById($permission);
        }

        if (\is_string($permission)) {
            return $this->canByName($permission);
        }

        return false;
    }
}
