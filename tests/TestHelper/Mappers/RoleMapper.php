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

use Linna\Authorization\EnhancedUserMapperInterface;
use Linna\Authentication\Password;
use Linna\Authorization\PermissionMapperInterface;
use Linna\Authorization\Role;
use Linna\Authorization\RoleMapperInterface;
use Linna\Authentication\User;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\MapperAbstract;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\ExtendedPDO;
use PDO;

/**
 * Role Mapper.
 */
class RoleMapper extends MapperAbstract implements RoleMapperInterface
{
    /**
     * @var Password Password util for user object
     */
    protected $password;

    /**
     * @var ExtendedPDO Database Connection
     */
    protected $pdo;

    /**
     * @var PermissionMapperInterface Permission Mapper
     */
    protected $permissionMapper;

    /**
     * @var EnhancedUserMapperInterface Permission Mapper
     */
    protected $userMapper;

    /**
     * Constructor.
     *
     * @param ExtendedPDO                 $pdo
     * @param Password                    $password
     * @param EnhancedUserMapperInterface $userMapper
     * @param PermissionMapperInterface   $permissionMapper
     */
    public function __construct(
            ExtendedPDO $pdo,
            Password $password,
            EnhancedUserMapperInterface $userMapper,
            PermissionMapperInterface $permissionMapper
    ) {
        $this->pdo = $pdo;
        $this->password = $password;
        $this->userMapper = $userMapper;
        $this->permissionMapper = $permissionMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $roleId): DomainObjectInterface
    {
        $pdos = $this->pdo->prepare('SELECT role_id AS objectId, name, description, active, last_update AS lastUpdate FROM role WHERE role_id = :id');

        $pdos->bindParam(':id', $roleId, PDO::PARAM_INT);
        $pdos->execute();

        $role = $pdos->fetchObject(Role::class);

        if (!($role instanceof Role)) {
            return new NullDomainObject();
        }

        $roleUsers = $this->userMapper->fetchUserByRole($roleId);
        $rolePermissions = $this->permissionMapper->fetchPermissionsByRole($roleId);

        $role->setUsers($roleUsers);
        $role->setPermissions($rolePermissions);

        return $role;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(): array
    {
        $pdos = $this->pdo->prepare('SELECT role_id AS objectId, name, description, active, last_update AS lastUpdate FROM role ');

        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, Role::class);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount): array
    {
        $pdos = $this->pdo->prepare('SELECT role_id AS objectId, name, description, active, last_update AS lastUpdate FROM groups LIMIT :offset, :rowcount');

        $pdos->bindParam(':offset', $offset, PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, PDO::PARAM_INT);
        $pdos->execute();

        $roles = $pdos->fetchAll(PDO::FETCH_CLASS, Role::class);

        return $this->fillRolesArray($roles);
    }

    /**
     * Set Permission and Users on every Role instance inside passed array.
     *
     * @param array $roles
     *
     * @return array
     */
    protected function fillRolesArray(array $roles): array
    {
        $arrayRoles = [];

        foreach ($roles as $role) {
            $roleId = $role->getId();

            $roleUsers = $this->userMapper->fetchUserByRole($roleId);
            $rolePermissions = $this->permissionMapper->fetchPermissionsByRole($roleId);

            $role->setUsers($roleUsers);
            $role->setPermissions($rolePermissions);
            $arrayRoles[] = $role;
        }

        return $arrayRoles;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchUserInheritedPermissions(Role &$role, User $user): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function permissionGrant(Role &$role, string $permission)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function permissionRevoke(Role &$role, string $permission)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function userAdd(Role &$role, User $user)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function userRemove(Role &$role, User $user)
    {
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
    protected function concreteInsert(DomainObjectInterface $role): string
    {
        return 'insert';
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteUpdate(DomainObjectInterface $role)
    {
        return 'update';
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteDelete(DomainObjectInterface $role)
    {
        return 'delete';
    }
}
