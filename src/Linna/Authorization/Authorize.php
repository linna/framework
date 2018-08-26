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
use Linna\DataMapper\NullDomainObject;

/**
 * Provide methods for check permissions for authenticated user.
 */
class Authorize
{
    /**
     * @var PermissionMapperInterface Permission Mapper
     */
    protected $permissionMapper;

    /**
     * @var Authentication Current authentication status
     */
    protected $authentication;

    /**
     * @var int User id
     */
    protected $userId = 0;

    /**
     * @var array User/Permission hash table
     */
    protected $hashTable;

    /**
     * Class Constructor.
     * <pre><code class="php">use Linna\Auth\Authentication;
     * use Linna\Auth\Authorize;
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
     * $authorize = new Authorize($authentication, $permissionMapper);
     * </code></pre>
     *
     * @param Authentication            $authentication
     * @param PermissionMapperInterface $permissionMapper
     */
    public function __construct(Authentication $authentication, PermissionMapperInterface $permissionMapper)
    {
        $this->authentication = $authentication;
        $this->permissionMapper = $permissionMapper;

        $this->userId = $authentication->getLoginData()['user_id'] ?? 0;

        $this->hashTable = $permissionMapper->fetchUserPermissionHashTable($this->userId);
    }

    /**
     * Check if authenticated user has a permission.
     * <pre><code class="php">$authorize = new Authorize($authentication, $permissionMapper);
     *
     * //with this example, the class checks if the authenticated
     * //user has the permission 'update user'.
     * $authorize->can('update user');
     * </code></pre>
     *
     * @param string $permissionName
     *
     * @return bool
     */
    public function can(string $permissionName): bool
    {
        //get permission
        $permission = $this->permissionMapper->fetchByName($permissionName);

        //permission not exist
        if ($permission instanceof NullDomainObject) {
            return false;
        }

        //check if there is user logged
        if (!$this->userId) {
            return false;
        }

        //make hash for hash table check
        $hash = hash('sha256', $this->userId.'.'.$permission->getId());

        return isset($this->hashTable[$hash]);
    }
}
