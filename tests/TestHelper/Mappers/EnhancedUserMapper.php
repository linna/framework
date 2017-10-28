<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mappers;

use Linna\Authorization\EnhancedUser;
use Linna\Authorization\EnhancedUserMapperInterface;
use Linna\Authentication\Password;
use Linna\Authorization\PermissionMapperInterface;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\PdoStorage;

/**
 * EnhancedUserMapper.
 */
class EnhancedUserMapper extends UserMapper implements EnhancedUserMapperInterface
{
    /**
     * @var PermissionMapperInterface Permission Mapper
     */
    protected $permissionMapper;

    /**
     * Constructor.
     *
     * @param PdoStorage                $dBase
     * @param Password                  $password
     * @param PermissionMapperInterface $permissionMapper
     */
    public function __construct(PdoStorage $dBase, Password $password, PermissionMapperInterface $permissionMapper)
    {
        parent::__construct($dBase, $password);

        $this->permissionMapper = $permissionMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $userId) : DomainObjectInterface
    {
        $pdos = $this->dBase->prepare('SELECT user_id AS objectId, name, email, description, password, active, created, last_update AS lastUpdate FROM user WHERE user_id = :id');

        $pdos->bindParam(':id', $userId, \PDO::PARAM_INT);
        $pdos->execute();

        $user = $pdos->fetchObject('\Linna\Authorization\EnhancedUser', [$this->password]);

        if (!($user instanceof EnhancedUser)) {
            return new NullDomainObject();
        }

        $user->setPermissions($this->permissionMapper->fetchPermissionsByUser($userId));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByName(string $userName) : DomainObjectInterface
    {
        $hashedUserName = md5($userName);

        $pdos = $this->dBase->prepare('SELECT user_id AS objectId, name, email, description, password, active, created, last_update AS lastUpdate FROM user WHERE md5(name) = :name');

        $pdos->bindParam(':name', $hashedUserName, \PDO::PARAM_STR);
        $pdos->execute();

        $user = $pdos->fetchObject('\Linna\Authorization\EnhancedUser', [$this->password]);

        if (!($user instanceof EnhancedUser)) {
            return new NullDomainObject();
        }

        $user->setPermissions($this->permissionMapper->fetchPermissionsByUser($user->getId()));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll() : array
    {
        $pdos = $this->dBase->prepare('SELECT user_id AS objectId, name, email, description, password, active, created, last_update AS lastUpdate FROM user ORDER BY name ASC');

        $pdos->execute();

        $users = $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authorization\EnhancedUser', [$this->password]);

        return $this->setUserPermission($users);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount) : array
    {
        $pdos = $this->dBase->prepare('SELECT user_id AS objectId, name, email, description, password, active, created, last_update AS lastUpdate FROM user ORDER BY name ASC LIMIT :offset, :rowcount');

        $pdos->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, \PDO::PARAM_INT);
        $pdos->execute();

        $users = $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authorization\EnhancedUser', [$this->password]);

        return $this->setUserPermission($users);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchUserByRole(int $roleId) : array
    {
        $pdos = $this->dBase->prepare('SELECT u.user_id AS objectId, name, email, description, password, active, created, last_update AS lastUpdate
        FROM user AS u INNER JOIN user_role AS ur ON u.user_id = ur.user_id
        WHERE role_id = :id');

        $pdos->bindParam(':id', $roleId, \PDO::PARAM_INT);
        $pdos->execute();

        $users = $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authorization\EnhancedUser', [$this->password]);

        return $this->setUserPermission($users);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchUserByPermission(int $permissionId) : array
    {
        return [];
    }

    /**
     * Set Permission on every EnhancedUser instance inside an array.
     *
     * @param array $users
     *
     * @return array
     */
    protected function setUserPermission(array $users) : array
    {
        $tempArray = [];

        foreach ($users as $user) {
            $user->setPermissions($this->permissionMapper->fetchPermissionsByUser($user->getId()));
            $tempArray[] = $user;
        }

        return $tempArray;
    }

    /**
     * {@inheritdoc}
     */
    public function grant(EnhancedUser &$user, string $permission)
    {
        if ($this->permissionMapper->permissionExist($permission)) {
            $pdos = $this->dBase->prepare('INSERT INTO user_permission (user_id, permission_id) VALUES (:user_id, (SELECT permission_id FROM permission WHERE name = :permission))');

            $userId = $user->getId();

            $pdos->bindParam(':user_id', $userId, \PDO::PARAM_INT);
            $pdos->bindParam(':permission', $permission, \PDO::PARAM_STR);
            $pdos->execute();

            $user->setPermissions($this->permissionMapper->fetchPermissionsByUser($userId));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function revoke(EnhancedUser &$user, string $permission)
    {
        if ($this->permissionMapper->permissionExist($permission)) {
            $pdos = $this->dBase->prepare('DELETE FROM user_permission WHERE user_id = :user_id AND permission_id = (SELECT permission_id FROM permission WHERE name = :permission)');

            $userId = $user->getId();

            $pdos->bindParam(':user_id', $userId, \PDO::PARAM_INT);
            $pdos->bindParam(':permission', $permission, \PDO::PARAM_STR);
            $pdos->execute();

            $user->setPermissions($this->permissionMapper->fetchPermissionsByUser($userId));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteCreate() : DomainObjectInterface
    {
        return new EnhancedUser($this->password);
    }
}
