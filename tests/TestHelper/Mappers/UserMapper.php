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

use InvalidArgumentException;
use Linna\Authentication\Password;
use Linna\Authentication\User;
use Linna\Authentication\UserMapperInterface;
use Linna\DataMapper\DomainObjectAbstract;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\MapperAbstract;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\ExtendedPDO;
use PDO;
use RuntimeException;

/**
 * UserMapper.
 */
class UserMapper extends MapperAbstract implements UserMapperInterface
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
     * Constructor.
     *
     * @param ExtendedPDO $pdo
     * @param Password   $password
     */
    public function __construct(ExtendedPDO $pdo, Password $password)
    {
        $this->pdo = $pdo;
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $userId): DomainObjectInterface
    {
        $pdos = $this->pdo->prepare('SELECT user_id AS objectId, uuid, name, email, description, password, active, created, last_update AS lastUpdate FROM user WHERE user_id = :id');

        $pdos->bindParam(':id', $userId, PDO::PARAM_INT);
        $pdos->execute();

        $result = $pdos->fetchObject(User::class, [$this->password]);

        return ($result instanceof User) ? $result : new NullDomainObject();
    }

    /**
     * Fetch a user object by name.
     *
     * @param string $userName
     *
     * @return DomainObjectAbstract
     */
    public function fetchByName(string $userName): DomainObjectInterface
    {
        $pdos = $this->pdo->prepare('SELECT user_id AS objectId, uuid, name, email, description, password, active, created, last_update AS lastUpdate FROM user WHERE md5(name) = :name');

        $hashedUserName = md5($userName);

        $pdos->bindParam(':name', $hashedUserName, PDO::PARAM_STR);
        $pdos->execute();

        $result = $pdos->fetchObject(User::class, [$this->password]);

        return ($result instanceof User) ? $result : new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(): array
    {
        $pdos = $this->pdo->prepare('SELECT user_id AS objectId, uuid, name, email, description, password, active, created, last_update AS lastUpdate FROM user ORDER BY name ASC');

        $pdos->execute();

        return $pdos->fetchAll(PDO::FETCH_CLASS, User::class, [$this->password]);
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

        return $pdos->fetchAll(PDO::FETCH_CLASS, User::class, [$this->password]);
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteCreate(): DomainObjectInterface
    {
        return new User($this->password);
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteInsert(DomainObjectInterface $user): int
    {
        $this->checkValidDomainObject($user);

        try {
            $pdos = $this->pdo->prepare('INSERT INTO user (uuid, name, email, description, password, created) VALUES (:uuid, :name, :email, :description, :password, NOW())');

            $pdos->bindParam(':uuid', $user->uuid, PDO::PARAM_STR);
            $pdos->bindParam(':name', $user->name, PDO::PARAM_STR);
            $pdos->bindParam(':email', $user->email, PDO::PARAM_STR);
            $pdos->bindParam(':description', $user->description, PDO::PARAM_STR);
            $pdos->bindParam(':password', $user->password, PDO::PARAM_STR);
            $pdos->execute();

            return (int) $this->pdo->lastInsertId();
        } catch (RuntimeException $e) {
            echo 'Insert not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteUpdate(DomainObjectInterface $user)
    {
        $this->checkValidDomainObject($user);

        try {
            $pdos = $this->pdo->prepare('UPDATE user SET name = :name, email = :email, description = :description,  password = :password, active = :active WHERE user_id = :user_id');

            $objId = $user->getId();

            $pdos->bindParam(':user_id', $objId, PDO::PARAM_INT);

            $pdos->bindParam(':name', $user->name, PDO::PARAM_STR);
            $pdos->bindParam(':email', $user->email, PDO::PARAM_STR);
            $pdos->bindParam(':password', $user->password, PDO::PARAM_STR);
            $pdos->bindParam(':description', $user->description, PDO::PARAM_STR);
            $pdos->bindParam(':active', $user->active, PDO::PARAM_INT);

            $pdos->execute();
        } catch (RuntimeException $e) {
            echo 'Update not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteDelete(DomainObjectInterface $user)
    {
        $this->checkValidDomainObject($user);

        try {
            $objId = $user->getId();
            $pdos = $this->pdo->prepare('DELETE FROM user WHERE user_id = :user_id');
            $pdos->bindParam(':user_id', $objId, PDO::PARAM_INT);
            $pdos->execute();
        } catch (RuntimeException $e) {
            echo 'Delete not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * Check for valid domain Object.
     *
     * @param DomainObjectInterface $user
     *
     * @throws InvalidArgumentException
     */
    protected function checkValidDomainObject(DomainObjectInterface &$user)
    {
        if (!($user instanceof User)) {
            throw new InvalidArgumentException('$user parameter must be instance of User class');
        }
    }
}
