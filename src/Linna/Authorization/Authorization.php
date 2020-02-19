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

use Linna\Authentication\Authentication;
use Linna\Authorization\Permission;

/**
 * Provide methods for check permissions for authenticated user.
 */
class Authorization
{
    use PermissionTrait {
        PermissionTrait::can as canByObject;
    }

    /**
     * @var int User id
     */
    protected int $userId = 0;

    /**
     * Class Constructor.
     * <pre><code class="php">use Linna\Auth\Authentication;
     * use Linna\Auth\Authorization;
     * use Linna\Auth\Password;
     * use Linna\Session\Session;
     *
     * //your concrete permission mapper
     * use YourApp\Mapper\PermissionMapper;
     *
     * $password = new Password();
     * $session = new Session();
     *
     * $authentication = new Authentication($session, $password);
     * $permissionMapper = new PermissionMapper();
     *
     * $authorization = new Authorization($authentication, $permissionMapper);
     * </code></pre>
     *
     * @param Authentication            $authentication
     * @param PermissionMapperInterface $permissionMapper
     */
    public function __construct(Authentication $authentication, PermissionMapperInterface $permissionMapper)
    {
        $this->userId = $authentication->getLoginData()['user_id'] ?? 0;
        $this->permission = $permissionMapper->fetchByUserId($this->userId);
    }

    /**
     * Check if authenticated user has a permission.
     * <pre><code class="php">$authorization = new Authorization($authentication, $permissionMapper);
     *
     * //with this example, the class checks if the authenticated
     * //user has the permission with the permission object.
     *
     * $permission = $permissionMapper->fetchById(1);
     * $authorization->can($permission);
     *
     * //with this example, the class checks if the authenticated
     * //user has the permission with the permission id 1.
     * $authorization->can(1);
     *
     * //with this example, the class checks if the authenticated
     * //user has the permission 'see users'.
     * $authorization->can('see users');
     * </code></pre>
     *
     * @param mixed $permission
     *
     * @return bool
     */
    public function can($permission): bool
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
