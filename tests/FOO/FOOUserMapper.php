<?php

/**
 * Linna App
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\MapperAbstract;
//use Linna\Database\Database;
use Linna\Auth\Password;

use Linna\FOO\FOOUser;

/**
 * UserMapper
 *
 */
class FOOUserMapper extends MapperAbstract
{
    /**
     * @var object $password Password util for user object
     */
    protected $password;

    /**
     * Constructor
     *
     */
    public function __construct(Password $password)
    {
        $this->password = $password;
    }

    /**
     * Fetch a user object by id
     *
     * @param string $userId
     *
     * @return User
     */
    public function findById($userId)
    {
        $user = $this->create();
        
        $user->setId($userId);
        
        $user->name = 'user_'.$userId;
        
        return $user;
    }

    /**
     * Create a new User DomainObject
     *
     * @return User
     */
    protected function oCreate()
    {
        return new FOOUser($this->password);
    }

    /**
     * Insert the DomainObject in persistent storage
     *
     * @param DomainObjectInterface $user
     */
    protected function oInsert(DomainObjectInterface $user)
    {
        if (!($user instanceof FOOUser)) {
            throw new \Exception('$user must be instance of User class');
        }

        try {
            return 'insert';
        } catch (\Exception $e) {
            echo 'Mapper exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     * Update the DomainObject in persistent storage
     *
     * @param DomainObjectInterface $user
     */
    protected function oUpdate(DomainObjectInterface $user)
    {
        if (!($user instanceof FOOUser)) {
            throw new \Exception('$user must be instance of User class');
        }

        try {
            return 'update';
        } catch (\Exception $e) {
            echo 'Mapper exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     * Delete the DomainObject from persistent storage
     *
     * @param DomainObjectAbstract $user
     */
    protected function oDelete(DomainObjectInterface $user)
    {
        if (!($user instanceof FOOUser)) {
            throw new \Exception('$user must be instance of User class');
        }

        try {
            return 'delete';
        } catch (\Exception $e) {
            echo 'Mapper exception: ', $e->getMessage(), "\n";
        }
    }
}
