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
     * @var Authenticate Current authentication status
     */
    protected $authenticate;

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
     * <pre><code class="php">use Linna\Auth\Authenticate;
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
     * $authenticate = new Authenticate($session, $password);
     * $permissionMapper = new PermissionMapper();
     * 
     * $authorize = new Authorize($authenticate, $permissionMapper);
     * </code></pre>
     * 
     * @param Authenticate              $authenticate
     * @param PermissionMapperInterface $permissionMapper
     */
    public function __construct(Authenticate $authenticate, PermissionMapperInterface $permissionMapper)
    {
        $this->authenticate = $authenticate;
        $this->permissionMapper = $permissionMapper;

        $this->userId = $authenticate->getLoginData()['user_id'] ?? 0;

        $this->hashTable = $permissionMapper->fetchUserPermissionHashTable($this->userId);
    }

    /**
     * Check if authenticated user has a permission.
     * <pre><code class="php">$authorize = new Authorize($authenticate, $permissionMapper);
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
    public function can(string $permissionName) : bool
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
