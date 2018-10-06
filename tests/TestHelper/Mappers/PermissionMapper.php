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

use Linna\Authorization\Permission;
use Linna\Authorization\PermissionMapperInterface;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\MapperAbstract;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\ExtendedPDO;
use PDO;

/**
 * PermissionMapper.
 */
class PermissionMapper extends MapperAbstract implements PermissionMapperInterface
{
    /**
     * @var ExtendedPDO Database Connection
     */
    protected $pdo;

    /**
     * Constructor.
     *
     * @param ExtendedPDO $pdo
     */
    public function __construct(ExtendedPDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $permissionId): DomainObjectInterface
    {
        $pdos = $this->pdo->prepare('SELECT permission_id AS objectId, name, description, last_update AS lastUpdate FROM permission WHERE permission_id = :id');

        $pdos->bindParam(':id', $permissionId, PDO::PARAM_INT);
        $pdos->execute();

        $result = $pdos->fetchObject(Permission::class);

        return ($result instanceof Permission) ? $result : new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByName(string $permissionName): DomainObjectInterface
    {
        $pdos = $this->pdo->prepare('SELECT permission_id AS objectId, name, description, last_update AS lastUpdate FROM permission WHERE name = :name');

        $pdos->bindParam(':name', $permissionName, PDO::PARAM_STR);
        $pdos->execute();

        $result = $pdos->fetchObject(Permission::class);

        return ($result instanceof Permission) ? $result : new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(): array
    {
        $pdos = $this->pdo->prepare('SELECT permission_id AS objectId, name, description, last_update AS lastUpdate FROM permission');

        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, Permission::class);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount): array
    {
        $pdos = $this->pdo->prepare('SELECT permission_id AS objectId, name, description, last_update AS lastUpdate FROM permission LIMIT :offset, :rowcount');

        $pdos->bindParam(':offset', $offset, PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, Permission::class);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPermissionsByRole(int $roleId): array
    {
        $pdos = $this->pdo->prepare('
        SELECT rp.permission_id AS objectId, name, description, last_update AS lastUpdate
        FROM permission AS p
        INNER JOIN role_permission AS rp 
        ON rp.permission_id = p.permission_id
        WHERE rp.role_id = :id');

        $pdos->bindParam(':id', $roleId, PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, Permission::class);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPermissionsByUser(int $userId): array
    {
        $pdos = $this->pdo->prepare('
        SELECT up.permission_id AS objectId, name, description, last_update AS lastUpdate
        FROM permission AS p
        INNER JOIN user_permission AS up 
        ON up.permission_id = p.permission_id
        WHERE up.user_id = :id');

        $pdos->bindParam(':id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, Permission::class);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchUserPermissionHashTable(int $userId): array
    {
        $pdos = $this->pdo->prepare("(SELECT sha2(concat(u.user_id, '.', up.permission_id),0) as p_hash
        FROM user AS u
        INNER JOIN user_permission AS up
        ON u.user_id = up.permission_id WHERE u.user_id = :id)

        UNION

        (SELECT sha2(concat(u.user_id, '.', rp.permission_id),0) as p_hash
        FROM user AS u
        INNER JOIN user_role AS ur
        INNER JOIN role AS r
        INNER JOIN role_permission as rp
        ON u.user_id = ur.user_id
        AND ur.role_id = r.role_id
        AND r.role_id = rp.role_id WHERE u.user_id = :id)

        ORDER BY p_hash");

        $pdos->bindParam(':id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        return array_flip($pdos->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * {@inheritdoc}
     */
    public function permissionExist(string $permission): bool
    {
        $pdos = $this->pdo->prepare('SELECT permission_id FROM permission WHERE name = :name');

        $pdos->bindParam(':name', $permission, PDO::PARAM_STR);
        $pdos->execute();

        return ($pdos->rowCount() > 0) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteCreate(): DomainObjectInterface
    {
        return new Permission();
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteInsert(DomainObjectInterface $permission): string
    {
        return 'insert';
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteUpdate(DomainObjectInterface $permission)
    {
        return 'update';
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteDelete(DomainObjectInterface $permission)
    {
        return 'delete';
    }
}
