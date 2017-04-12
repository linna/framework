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
 * Check Permission for authenticated user.
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
     * Constructor.
     *
     * @param Authenticate              $authenticate
     * @param PermissionMapperInterface $permissionMapper
     */
    public function __construct(Authenticate $authenticate, PermissionMapperInterface $permissionMapper)
    {
        $this->authenticate = $authenticate;
        $this->permissionMapper = $permissionMapper;

        $this->userId = $authenticate->data['user_id'] ?? 0;

        $this->hashTable = $permissionMapper->fetchUserPermissionHashTable($this->userId);
    }

    /**
     * Check if authenticated user has a permission.
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
