<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\Auth;

use Leviu\Database\DomainObjectAbstract;
use Leviu\Database\MapperAbstract;
use Leviu\Database\Database;

use Leviu\Auth\User;
/**
 * UserMapper
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class UserMapper extends MapperAbstract
{
    /**
     * @var object Database Connection
     */
    protected $db;
    
    /**
     * UserMapper Constructor
     * 
     * Open only a database connection
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * findById
     * 
     * Fetch a user object by id
     * 
     * @param string $id
     * @return User
     * @since 0.1.0
     */
    public function findById($id)
    {
        $pdos = $this->db->prepare("SELECT user_id AS _id, name, description, password, active, created, last_update FROM user WHERE user_id = :id");

        $pdos->bindParam(':id', $id, \PDO::PARAM_INT);
        $pdos->execute();
        
        return $pdos->fetchObject('\App_mk0\User');//$this->create($pdos->fetch());
    }

    /**
     * findByName
     * 
     * Fetch a user object by name
     * 
     * @param string $name
     * @return User
     * @since 0.1.0
     */
    public function findByName($name)
    {
        $pdos = $this->db->prepare("SELECT user_id AS _id, name, description, password, active, created, last_update FROM user WHERE md5(name) = :name");

        $hashedUserName = md5($name);

        $pdos->bindParam(':name', $hashedUserName, \PDO::PARAM_STR);
        $pdos->execute();
        
        return $pdos->fetchObject('\Leviu\Auth\User');//$this->create($pdos->fetch());
    }

    
    public function getAllUsers()
    {
        $pdos = $this->db->prepare("SELECT user_id as _id, name, description, password, active, created, last_update FROM user ORDER BY name ASC");
        
        $pdos->execute();
        
        return $pdos->fetchAll(\PDO::FETCH_CLASS, '\Leviu\Auth\User');
    }
    
    /**
     * populate
     * 
     * Populate the User (DomainObject) with
     * the data.
     * 
     * @param DomainObjectAbstract $obj
     * @param object $data
     * @return User
     * @since 0.1.0
     * @deprecated since version 0.1.0 Replaced with \PDO::FETCH_CLASS fetch option
     */
    public function populate(DomainObjectAbstract $obj, $data)
    {
        $obj->setId($data->user_id);
        $obj->name = $data->name;
        $obj->description = $data->description;
        $obj->password = $data->password;
        $obj->active = (int)$data->active;
        $obj->created = $data->created;
        $obj->last_update = $data->last_update;

        return $obj;
    }

    /**
     * _create
     * 
     * Create a new User DomainObject
     *
     * @return User
     * @since 0.1.0
     */
    protected function _create()
    {
        return new User();
    }

    /**
     * _insert
     * 
     * Insert the DomainObject in persistent storage
     * 
     * This may include connecting to the database
     * and running an insert statement.
     *
     * @param DomainObjectAbstract $obj
     * @since 0.1.0
     */
    protected function _insert(DomainObjectAbstract $obj)
    {
        $pdos = $this->db->prepare("INSERT INTO user (name, description, password, created) VALUES (:name, :description, :password, NOW())");

        $pdos->bindParam(':name', $obj->name, \PDO::PARAM_STR);
        $pdos->bindParam(':description', $obj->description, \PDO::PARAM_STR);
        $pdos->bindParam(':password', $obj->password, \PDO::PARAM_STR);
        $pdos->execute();
        
        return $this->db->lastInsertId();
    }

    /**
     * _update
     * 
     * Update the DomainObject in persistent storage
     * 
     * This may include connecting to the database
     * and running an update statement.
     *
     * @param DomainObjectAbstract $obj
     * @since 0.1.0
     */
    protected function _update(DomainObjectAbstract $obj)
    {
        $pdos = $this->db->prepare("UPDATE user SET name = :name, description = :description,  password = :password, active = :active WHERE user_id = :user_id");
        
        $pdos->bindParam(':user_id', $obj->getId(), \PDO::PARAM_INT);
        
        $pdos->bindParam(':name', $obj->name, \PDO::PARAM_STR);
        $pdos->bindParam(':password', $obj->password, \PDO::PARAM_STR);
        $pdos->bindParam(':description', $obj->description, \PDO::PARAM_STR);
        $pdos->bindParam(':active', $obj->active, \PDO::PARAM_INT);
        
        $pdos->execute();
    }

    /**
     * __delete
     * 
     * Delete the DomainObject from persistent storage
     * 
     * This may include connecting to the database
     * and running a delete statement.
     *
     * @param DomainObjectAbstract $obj
     * @since 0.1.0
     */
    protected function _delete(DomainObjectAbstract $obj)
    {
        $pdos = $this->db->prepare("DELETE FROM user WHERE user_id = :user_id");
        
        $pdos->bindParam(':user_id', $obj->getId(), \PDO::PARAM_INT);
        
        $pdos->execute();
    }
}
