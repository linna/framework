<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mappers;

use Linna\Authentication\UserMapperInterface;
use Linna\Authorization\EnhancedUser;
use Linna\Authorization\Permission;
use Linna\Authorization\PermissionMapperInterface;
use Linna\Authorization\Role;
use Linna\Authorization\RoleMapperInterface;
use Linna\Authorization\RoleToUserMapperInterface;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\MapperAbstract;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\ExtendedPDO;
use PDO;
use PDOStatement;
use PDOException;

/**
 * Role Mapper.
 */
class RoleMapper extends MapperAbstract implements RoleMapperInterface
{
    /**
     * @var ExtendedPDO Database Connection
     */
    protected $pdo;

    /**
     * @var PermissionMapperInterface Permission Mapper
     */
    protected $permissionMapper;

    /**
     * @var UserMapperInterface Permission Mapper
     */
    protected $userMapper;

    /**
     * @var RoleToUserMapperInterface Role To User Mapper
     */
    protected $roleToUserMapper;

    /**
     * Constructor.
     *
     * @param ExtendedPDO               $pdo
     * @param PermissionMapperInterface $permissionMapper
     * @param UserMapperInterface       $userMapper
     * @param RoleToUserMapperInterface $roleToUserMapper
     */
    public function __construct(
        ExtendedPDO $pdo,
        PermissionMapperInterface $permissionMapper,
        UserMapperInterface $userMapper,
        RoleToUserMapperInterface $roleToUserMapper
    ) {
        $this->pdo = $pdo;
        $this->permissionMapper = $permissionMapper;
        $this->userMapper = $userMapper;
        $this->roleToUserMapper = $roleToUserMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $roleId): DomainObjectInterface
    {
        $users = $this->roleToUserMapper->fetchByRoleId($roleId);
        $permissions = $this->permissionMapper->fetchByRoleId($roleId);

        $pdos = $this->pdo->prepare('SELECT role_id AS objectId, name, description, active, last_update AS lastUpdate FROM role WHERE role_id = :id');
        $pdos->bindParam(':id', $roleId, PDO::PARAM_INT);
        $pdos->execute();

        $role = $pdos->fetchObject(Role::class, [$users, $permissions]);

        unset($users, $permissions);

        if ($role instanceof Role) {
            return $role;
        }

        return new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(): array
    {
        $pdos = $this->pdo->prepare('SELECT role_id AS objectId, name, description, active, last_update AS lastUpdate FROM role');
        $pdos->execute();

        return $this->roleCompositor($pdos);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount): array
    {
        $pdos = $this->pdo->prepare('SELECT role_id AS objectId, name, description, active, last_update AS lastUpdate FROM role LIMIT :offset, :rowcount');
        $pdos->bindParam(':offset', $offset, PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, PDO::PARAM_INT);
        $pdos->execute();

        return $this->roleCompositor($pdos);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByPermission(Permission $permission): array
    {
        return $this->fetchByPermissionId($permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByPermissionId(int $permissionId): array
    {
        $pdos = $this->pdo->prepare('
        SELECT r.role_id AS objectId, r.name, r.description, r.active, r.last_update AS lastUpdate
        FROM role AS r
        INNER JOIN role_permission AS rp
        ON r.role_id = rp.role_id
        WHERE rp.permission_id = :id');

        $pdos->bindParam(':id', $permissionId, PDO::PARAM_INT);
        $pdos->execute();

        return $this->roleCompositor($pdos);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByPermissionName(string $permissionName): array
    {
        $permission = $this->permissionMapper->fetchByName($permissionName);

        return $this->fetchByPermissionId($permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByUser(EnhancedUser $user): array
    {
        return $this->fetchByUserId($user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByUserId(int $userId): array
    {
        $pdos = $this->pdo->prepare('
        SELECT r.role_id AS objectId, r.name, r.description, r.active, r.last_update AS lastUpdate
        FROM role AS r
        INNER JOIN user_role AS ur
        ON r.role_id = ur.role_id
        WHERE ur.user_id = :id');

        $pdos->bindParam(':id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        return $this->roleCompositor($pdos);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByUserName(string $userName): array
    {
        $user = $this->userMapper->fetchByName($userName);

        return $this->fetchByUserId($user->getId());
    }

    /**
     * Role Compositor.
     * Build roles array creating every instance for roles retrived from
     * persistent storage.
     *
     * @param PDOStatement $pdos
     *
     * @return array
     */
    protected function roleCompositor(PDOStatement &$pdos): array
    {
        $roles = [];

        while (($role = $pdos->fetch(PDO::FETCH_OBJ)) !== false) {
            $tmp = new Role(
                $this->roleToUserMapper->fetchByRoleId((int) $role->objectId),
                $this->permissionMapper->fetchByRoleId((int) $role->objectId)
            );

            $tmp->setId((int) $role->objectId);
            $tmp->active = (int) $role->active;
            $tmp->description = $role->description;
            $tmp->name = $role->name;
            $tmp->lastUpdate = $role->lastUpdate;

            $roles[] = $tmp;
        }

        return $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermission(Role &$role, Permission $permission)
    {
        $this->grantPermissionById($role, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermissionById(Role &$role, int $permissionId)
    {
        $roleId = $role->getId();

        try {
            $pdos = $this->pdo->prepare('INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)');

            $pdos->bindParam(':role_id', $roleId, PDO::PARAM_INT);
            $pdos->bindParam(':permission_id', $permissionId, PDO::PARAM_INT);
            $pdos->execute();

            $role = $this->fetchById($roleId);
        } catch (PDOException $e) {
            //here log the error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermissionByName(Role &$role, string $permissionName)
    {
        $permission = $this->permissionMapper->fetchByName($permissionName);

        $this->grantPermissionById($role, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermission(Role &$role, Permission $permission)
    {
        $this->revokePermissionById($role, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermissionById(Role &$role, int $permissionId)
    {
        $roleId = $role->getId();

        $pdos = $this->pdo->prepare('DELETE FROM role_permission WHERE role_id = :role_id AND permission_id = :permission_id');

        $pdos->bindParam(':role_id', $roleId, PDO::PARAM_INT);
        $pdos->bindParam(':permission_id', $permissionId, PDO::PARAM_INT);
        $pdos->execute();

        $role = $this->fetchById($roleId);
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermissionByName(Role &$role, string $permissionName)
    {
        $permission = $this->permissionMapper->fetchByName($permissionName);

        $this->revokePermissionById($role, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function addUser(Role &$role, EnhancedUser $user)
    {
        $this->addUserById($role, $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function addUserById(Role &$role, int $userId)
    {
        $roleId = $role->getId();

        try {
            $pdos = $this->pdo->prepare('INSERT INTO user_role (user_id, role_id) VALUES (:user_id, :role_id)');

            $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $pdos->bindParam(':role_id', $roleId, PDO::PARAM_INT);
            $pdos->execute();

            $role = $this->fetchById($roleId);
        } catch (PDOException $e) {
            //here log the error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addUserByName(Role &$role, string $userName)
    {
        $user = $this->userMapper->fetchByName($userName);

        $this->addUserById($role, $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function removeUser(Role &$role, EnhancedUser $user)
    {
        $this->removeUserById($role, $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserById(Role &$role, int $userId)
    {
        $roleId = $role->getId();

        $pdos = $this->pdo->prepare('DELETE FROM user_role WHERE role_id = :role_id AND user_id = :user_id');

        $pdos->bindParam(':role_id', $roleId, PDO::PARAM_INT);
        $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        $role = $this->fetchById($roleId);
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserByName(Role &$role, string $userName)
    {
        $user = $this->userMapper->fetchByName($userName);

        $this->removeUserById($role, $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteCreate(): DomainObjectInterface
    {
        return new Role();
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteInsert(DomainObjectInterface &$role): string
    {
        $this->checkDomainObjectType($role);

        try {
            $pdos = $this->pdo->prepare('INSERT INTO role (name, description, active) VALUES (:name, :description, :active)');

            $pdos->bindParam(':name', $role->name, PDO::PARAM_STR);
            $pdos->bindParam(':description', $role->description, PDO::PARAM_STR);
            $pdos->bindParam(':active', $role->active, PDO::PARAM_INT);
            $pdos->execute();

            $role->setId((int)$this->pdo->lastInsertId());
        } catch (RuntimeException $e) {
            echo 'Insert not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteUpdate(DomainObjectInterface $role)
    {
        $this->checkDomainObjectType($role);

        $objId = $role->getId();

        try {
            $pdos = $this->pdo->prepare('UPDATE role SET name = :name, description = :description, active = :active WHERE (role_id = :id)');

            $pdos->bindParam(':id', $objId, PDO::PARAM_INT);
            $pdos->bindParam(':name', $role->name, PDO::PARAM_STR);
            $pdos->bindParam(':description', $role->description, PDO::PARAM_STR);
            $pdos->bindParam(':active', $role->active, PDO::PARAM_INT);
            $pdos->execute();
        } catch (RuntimeException $e) {
            echo 'Update not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteDelete(DomainObjectInterface &$role)
    {
        $this->checkDomainObjectType($role);

        $objId = $role->getId();

        try {
            $pdos = $this->pdo->prepare('DELETE FROM role WHERE role_id = :id');

            $pdos->bindParam(':id', $objId, PDO::PARAM_INT);
            $pdos->execute();

            $role = new NullDomainObject();
        } catch (RuntimeException $e) {
            echo 'Delete not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDomainObjectType(DomainObjectInterface $domainObject)
    {
        if (!($domainObject instanceof Role)) {
            throw new InvalidArgumentException('Domain Object parameter must be instance of Role class');
        }
    }
}
