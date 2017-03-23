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

use Linna\Auth\Authenticate;
use Linna\Auth\PermissionMapperInterface;
use Linna\DataMapper\NullDomainObject;

/**
 * Check Permission for authenticated user.
 * 
 */
class Authorize
{
    /**
     * @var PermissionMapperInterface $permissionMapper Permission Mapper
     */
    protected $permissionMapper;
    
    /**
     * @var Authenticate $authenticate Current authentication status
     */
    protected $authenticate;
    
    /**
     * @var array $hashTable User/Permission hash table
     */
    protected $hashTable;
    
    /**
     * Constructor.
     */
    public function __construct(Authenticate $authenticate, PermissionMapperInterface $permissionMapper)
    {
        $this->authenticate = $authenticate;
        $this->permissionMapper = $permissionMapper;
        
        $this->hashTable = $this->populateHashTable();
    }
    
    /**
     * Populate Hash Table.
     * 
     * @return array
     */
    protected function populateHashTable() : array
    {
        return $this->permissionMapper->generatePermissionHashTable();
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
        
        //user not authenticated
        if ($this->authenticate->logged === false){
            return false;
        }
        
        //login without redirect check
        if (!isset($this->authenticate->data['user_id'])){
           return false;
        }
        
        //get user id
        $userId = $this->authenticate->data['user_id'];
        
        //make hash for hash table check
        $hash = hash('sha256', $userId.'.'.$permission->getId());
        
        return isset($this->hashTable[$hash]);
    }
}
