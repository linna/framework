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

use Linna\Authentication\Password;
use Linna\Authorization\EnhancedUser;
use Linna\Authorization\EnhancedUserMapperInterface;
use Linna\Authorization\Permission;
use Linna\Authorization\PermissionMapperInterface;
use Linna\Authorization\Role;
use Linna\Authorization\RoleToUserMapperInterface;
use Linna\Authentication\User;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\ExtendedPDO;
use PDO;
use PDOStatement;
use PDOException;

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
     * @var RoleMapperInterface Role Mapper
     */
    protected $roleToUserMapper;

    /**
     * Class Constructor.
     *
     * @param ExtendedPDO               $pdo
     * @param Password                  $password
     * @param PermissionMapperInterface $permissionMapper
     * @param RoleToUserMapperInterface $roleToUserMapper
     */
    public function __construct(
        ExtendedPDO $pdo,
        Password $password,
        PermissionMapperInterface $permissionMapper,
        RoleToUserMapperInterface $roleToUserMapper
    ) {
        parent::__construct($pdo, $password);

        $this->permissionMapper = $permissionMapper;
        $this->roleToUserMapper = $roleToUserMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $userId): DomainObjectInterface
    {
        $roles = $this->roleToUserMapper->fetchByUserId($userId);
        $permissions = $this->permissionMapper->fetchByUserId($userId);

        $pdos = $this->pdo->prepare('SELECT user_id AS objectId, uuid, name, email, description, password, active, created, last_update AS lastUpdate FROM user WHERE user_id = :id');
        $pdos->bindParam(':id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        $user = $pdos->fetchObject(EnhancedUser::class, [$this->password, $roles, $permissions]);

        unset($roles, $permissions);

        if ($user instanceof EnhancedUser) {
            return $user;
        }

        return new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByName(string $userName): DomainObjectInterface
    {
        $user = parent::fetchByName($userName);

        if ($user instanceof User) {
            return $this->fetchById($user->getId());
        }

        return new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(): array
    {
        $pdos = $this->pdo->prepare('SELECT user_id AS objectId, uuid, name, email, description, password, active, created, last_update AS lastUpdate FROM user ORDER BY name ASC');
        $pdos->execute();

        return $this->EnhancedUserCompositor($pdos);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount): array
    {
        $pdos = $this->pdo->prepare('SELECT user_id AS objectId, uuid, name, email, description, password, active, created, last_update AS lastUpdate FROM user ORDER BY name ASC LIMIT :offset, :rowcount');

        $pdos->bindParam(':offset', $offset, PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, PDO::PARAM_INT);
        $pdos->execute();

        return $this->EnhancedUserCompositor($pdos);
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
        SELECT u.user_id AS objectId, u.uuid, u.name, u.email, u.description, 
        u.password, u.active, u.created, u.last_update AS lastUpdate 
        FROM user u
        INNER JOIN user_permission AS up
        ON u.user_id = up.user_id
        WHERE up.permission_id = :id');

        $pdos->bindParam(':id', $permissionId, PDO::PARAM_INT);
        $pdos->execute();

        return $this->EnhancedUserCompositor($pdos);
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
    public function fetchByRole(Role $role): array
    {
        return $this->fetchByRoleId($role->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByRoleId(int $roleId): array
    {
        $pdos = $this->pdo->prepare('
        SELECT u.user_id AS objectId, u.uuid, u.name, u.email, u.description, 
        u.password, u.active, u.created, u.last_update AS lastUpdate
        FROM user AS u
        INNER JOIN user_role AS ur 
        ON u.user_id = ur.user_id
        WHERE ur.role_id = :id');

        $pdos->bindParam(':id', $roleId, PDO::PARAM_INT);
        $pdos->execute();

        return $this->EnhancedUserCompositor($pdos);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByRoleName(string $roleName): array
    {
        $pdos = $this->pdo->prepare('
        SELECT u.user_id AS objectId, u.uuid, u.name, u.email, u.description, 
        u.password, u.active, u.created, u.last_update AS lastUpdate
        FROM user AS u
        INNER JOIN user_role AS ur 
        INNER JOIN role AS r
        ON u.user_id = ur.user_id
        AND ur.role_id = r.role_id
        WHERE r.name = :name');

        $pdos->bindParam(':name', $roleName, PDO::PARAM_STR);
        $pdos->execute();

        return $this->EnhancedUserCompositor($pdos);
    }

    /**
     * EnhancedUser Compositor.
     * Build users array creating every instance for users retrived from
     * persistent storage.
     *
     * @param PDOStatement $pdos
     *
     * @return array
     */
    protected function EnhancedUserCompositor(PDOStatement &$pdos): array
    {
        $users = [];

        while (($user = $pdos->fetch(PDO::FETCH_OBJ)) !== false) {
            $tmp = new EnhancedUser(
                $this->password,
                $this->roleToUserMapper->fetchByUserId((int) $user->objectId),
                $this->permissionMapper->fetchByUserId((int) $user->objectId)
            );

            $tmp->setId((int) $user->objectId);
            $tmp->uuid = $user->uuid;
            $tmp->name = $user->name;
            $tmp->description = $user->description;
            $tmp->email = $user->email;
            $tmp->password = $user->password;
            $tmp->active =(int) $user->active;
            $tmp->created = $user->created;
            $tmp->lastUpdate = $user->lastUpdate;

            $users[] = $tmp;
        }

        return $users;
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermission(EnhancedUser &$user, Permission $permission)
    {
        $this->grantPermissionById($user, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermissionById(EnhancedUser &$user, int $permissionId)
    {
        $userId = $user->getId();

        try {
            $pdos = $this->pdo->prepare('INSERT INTO user_permission (user_id, permission_id) VALUES (:user_id, :permission_id)');

            $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $pdos->bindParam(':permission_id', $permissionId, PDO::PARAM_INT);
            $pdos->execute();

            $user = $this->fetchById($userId);
        } catch (PDOException $e) {
            //here log the error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermissionByName(EnhancedUser &$user, string $permissionName)
    {
        $permission = $this->permissionMapper->fetchByName($permissionName);

        $this->grantPermissionById($user, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermission(EnhancedUser &$user, Permission $permission)
    {
        $this->revokePermissionById($user, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermissionById(EnhancedUser &$user, int $permissionId)
    {
        $userId = $user->getId();

        $pdos = $this->pdo->prepare('DELETE FROM user_permission WHERE user_id = :user_id AND permission_id = :permission_id');

        $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $pdos->bindParam(':permission_id', $permissionId, PDO::PARAM_INT);
        $pdos->execute();

        $user = $this->fetchById($userId);
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermissionByName(EnhancedUser &$user, string $permissionName)
    {
        $permission = $this->permissionMapper->fetchByName($permissionName);

        $this->revokePermissionById($user, $permission->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function addRole(EnhancedUser &$user, Role $role)
    {
        $this->addRoleById($user, $role->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function addRoleById(EnhancedUser &$user, int $roleId)
    {
        $userId = $user->getId();

        try {
            $pdos = $this->pdo->prepare('INSERT INTO user_role (user_id, role_id) VALUES (:user_id, :role_id)');

            $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $pdos->bindParam(':role_id', $roleId, PDO::PARAM_INT);
            $pdos->execute();

            $user = $this->fetchById($userId);
        } catch (PDOException $e) {
            //here log the error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addRoleByName(EnhancedUser &$user, string $roleName)
    {
        $userId = $user->getId();

        try {
            $pdos = $this->pdo->prepare(
            'INSERT INTO user_role (user_id, role_id)
            VALUES (:user_id, (SELECT role_id FROM role WHERE name = :role_name))'
            );

            $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $pdos->bindParam(':role_name', $roleName, PDO::PARAM_STR);
            $pdos->execute();

            $user = $this->fetchById($userId);
        } catch (PDOException $e) {
            //here log the error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole(EnhancedUser &$user, Role $role)
    {
        $this->removeRoleById($user, $role->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function removeRoleById(EnhancedUser &$user, int $roleId)
    {
        $userId = $user->getId();

        $pdos = $this->pdo->prepare('DELETE FROM user_role WHERE role_id = :role_id AND user_id = :user_id');

        $pdos->bindParam(':role_id', $roleId, PDO::PARAM_INT);
        $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        $user = $this->fetchById($userId);
    }

    /**
     * {@inheritdoc}
     */
    public function removeRoleByName(EnhancedUser &$user, string $roleName)
    {
        $userId = $user->getId();

        $pdos = $this->pdo->prepare(
        'DELETE FROM user_role
        WHERE role_id = (SELECT role_id FROM role WHERE name = :role_name) 
        AND user_id = :user_id'
        );

        $pdos->bindParam(':role_id', $roleName, PDO::PARAM_INT);
        $pdos->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        $user = $this->fetchById($userId);
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteCreate(): DomainObjectInterface
    {
        return new EnhancedUser($this->password, [], []);
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDomainObjectType(DomainObjectInterface $domainObject)
    {
        if (!($domainObject instanceof EnhancedUser)) {
            throw new InvalidArgumentException('Domain Object parameter must be instance of EnhancedUser class');
        }
    }
}
