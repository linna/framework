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
use Linna\Authorization\Role;
use Linna\Authorization\RoleToUserMapperInterface;
use Linna\Storage\ExtendedPDO;
use PDO;

/**
 * Role To User Mapper.
 * Fetch only mapper utilized as helper for build Role and EnhancedUser mapper.
 */
class RoleToUserMapper implements RoleToUserMapperInterface
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
     * Class Constructor.
     *
     * @param ExtendedPDO $pdo
     * @param Password    $password
     */
    public function __construct(ExtendedPDO $pdo, Password $password)
    {
        $this->pdo = $pdo;
        $this->password = $password;
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
        SELECT u.user_id AS objectId, u.uuid, u.name, u.email, u.description, u.password, u.active, u.created, u.last_update AS lastUpdate
        FROM user AS u
        INNER JOIN user_role AS ur 
        ON u.user_id = ur.user_id
        WHERE ur.role_id = :id');

        $pdos->bindParam(':id', $roleId, PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, EnhancedUser::class, [$this->password, [], []]);
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

        return $pdos->fetchAll(PDO::FETCH_CLASS, EnhancedUser::class, [$this->password, [], []]);
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

        return $pdos->fetchAll(PDO::FETCH_CLASS, Role::class, [[], []]);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchByUserName(string $userName): array
    {
        $pdos = $this->pdo->prepare('
        SELECT r.role_id AS objectId, r.name, r.description, r.active, r.last_update AS lastUpdate
        FROM role AS r
        INNER JOIN user_role AS ur
        INNER JOIN user AS u
        ON r.role_id = ur.role_id
        AND ur.user_id = u.user_id
        WHERE u.name = :name');

        $pdos->bindParam(':name', $userName, PDO::PARAM_STR);
        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, Role::class, [[], []]);
    }
}
