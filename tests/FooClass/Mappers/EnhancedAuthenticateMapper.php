<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Foo\Mappers;

use Linna\Authentication\EnhancedAuthenticateMapperInterface;
use Linna\Authentication\LoginAttempt;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\MapperAbstract;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\PdoStorage;

/**
 * EnhancedAuthenticateMapper.
 */
class EnhancedAuthenticateMapper extends MapperAbstract implements EnhancedAuthenticateMapperInterface
{
    /**
     * @var \PDO Database Connection
     */
    protected $dBase;
    
    /**
     * Constructor.
     *
     * @param PdoStorage $dBase
     */
    public function __construct(PdoStorage $dBase)
    {
        $this->dBase = $dBase->getResource();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchById(int $loginAttemptId) : DomainObjectInterface
    {
        $pdos = $this->dBase->prepare('SELECT login_attempt_id AS objectId, session_id AS sessionId, ipv4, ipv6, date_time AS when, last_update AS lastUpdate FROM login_attempt WHERE login_attempt_id = :id');

        $pdos->bindParam(':id', $loginAttemptId, \PDO::PARAM_INT);
        $pdos->execute();

        $result = $pdos->fetchObject('\Linna\Authentication\LoginAttempt');

        return ($result instanceof LoginAttempt) ? $result : new NullDomainObject();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll() : array
    {
        $pdos = $this->dBase->prepare('SELECT login_attempt_id AS objectId, session_id AS sessionId, ipv4, ipv6, date_time AS when, last_update AS lastUpdate FROM login_attempt');

        $pdos->execute();

        return $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authentication\LoginAttempt');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount) : array
    {
        $pdos = $this->dBase->prepare('SELECT login_attempt_id AS objectId, session_id AS sessionId, ipv4, ipv6, date_time AS when, last_update AS lastUpdate FROM login_attempt ORDER BY date_time ASC LIMIT :offset, :rowcount');

        $pdos->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, \PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authentication\LoginAttempt');
    }
    
    /**
     * {@inheritdoc}
     */
    protected function concreteCreate() : DomainObjectInterface
    {
        return new LoginAttempt();
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteInsert(DomainObjectInterface $loginAttempt) : int
    {
        $this->checkValidDomainObject($loginAttempt);

        try {
            $pdos = $this->dBase->prepare('INSERT INTO login_attempt (session_id, ipv4, ipv6, date_time) VALUES (:session_id, :ipv4, :ipv6, :date_time)');

            $pdos->bindParam(':session_id', $loginAttempt->sessionId, \PDO::PARAM_STR);
            $pdos->bindParam(':ipv4', $loginAttempt->ipv4, \PDO::PARAM_STR);
            $pdos->bindParam(':ipv6', $loginAttempt->ipv4, \PDO::PARAM_STR);
            $pdos->bindParam(':date_time', $loginAttempt->when, \PDO::PARAM_STR);
            
            $pdos->execute();

            return (int) $this->dBase->lastInsertId();
        } catch (\RuntimeException $e) {
            echo 'Mapper: Insert not compled, ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteUpdate(DomainObjectInterface $loginAttempt)
    {
        $this->checkValidDomainObject($loginAttempt);

        try {
            $pdos = $this->dBase->prepare('UPDATE login_attempt SET session_id = :session_id, ipv4 = :ipv4, ipv6 = :ipv6,  date_time = :date_time WHERE login_attempt_id = :login_attempt_id');

            $objId = $loginAttempt->getId();

            $pdos->bindParam(':login_attempt_id', $objId, \PDO::PARAM_INT);

            $pdos->bindParam(':session_id', $loginAttempt->sessionId, \PDO::PARAM_STR);
            $pdos->bindParam(':ipv4', $loginAttempt->ipv4, \PDO::PARAM_STR);
            $pdos->bindParam(':ipv6', $loginAttempt->ipv4, \PDO::PARAM_STR);
            $pdos->bindParam(':date_time', $loginAttempt->when, \PDO::PARAM_STR);
            
            $pdos->execute();
        } catch (\Exception $e) {
            echo 'Mapper exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function concreteDelete(DomainObjectInterface $loginAttempt)
    {
        $this->checkValidDomainObject($loginAttempt);
        
        return 'delete';
    }
    
    /**
     * Check for valid domain Object.
     *
     * @param DomainObjectInterface $loginAttempt
     * @throws \InvalidArgumentException
     */
    protected function checkValidDomainObject(DomainObjectInterface &$loginAttempt)
    {
        if (!($loginAttempt instanceof LoginAttempt)) {
            throw new \InvalidArgumentException('$loginAttempt must be instance of LoginAttempt class');
        }
    }
}
