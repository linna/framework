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
        $pdos = $this->dBase->prepare('SELECT login_attempt_id AS objectId, user_name AS userName, session_id AS sessionId, ip, date_time AS when, last_update AS lastUpdate FROM login_attempt WHERE login_attempt_id = :id');

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
        $pdos = $this->dBase->prepare('SELECT login_attempt_id AS objectId, user_name AS userName, session_id AS sessionId, ip, date_time AS when, last_update AS lastUpdate FROM login_attempt');

        $pdos->execute();

        return $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authentication\LoginAttempt');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLimit(int $offset, int $rowCount) : array
    {
        $pdos = $this->dBase->prepare('SELECT login_attempt_id AS objectId, user_name AS userName, session_id AS sessionId, ip, date_time AS when, last_update AS lastUpdate FROM login_attempt ORDER BY date_time ASC LIMIT :offset, :rowcount');

        $pdos->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $pdos->bindParam(':rowcount', $rowCount, \PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchAll(\PDO::FETCH_CLASS, '\Linna\Authentication\LoginAttempt');
    }
    
    /**
     * Return how many login attempts did with the same user in specified time.
     *
     * @param string $userName      User name
     * @param int    $timeInSeconds Attempts in the last specified seconds
     */
    
    public function fetchAttemptsWithSameUser(string $userName, int $timeInSeconds) : int
    {
        $pdos = $this->dBase->prepare('SELECT count(user_name) as attempts FROM login_attempt WHERE user_name = :user_name AND date_time > (now() - :time)');

        $pdos->bindParam(':user_name', $userName, \PDO::PARAM_STR);
        $pdos->bindParam(':time', $timeInSeconds, \PDO::PARAM_STR);
        
        $pdos->execute();
        
        return (int) $pdos->fetch(\PDO::FETCH_LAZY)->attempts;
    }
    
    /**
     * Return how many login attempts did with the same session in specified time.
     *
     * @param string $sessionId     Session id
     * @param int    $timeInSeconds Attempts in the last specified seconds
     */
    public function fetchAttemptsWithSameSession(string $sessionId, int $timeInSeconds) : int
    {
        $pdos = $this->dBase->prepare('SELECT count(session_id) as attempts FROM login_attempt WHERE session_id = :session_id AND date_time > (now() - :time)');

        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->bindParam(':time', $timeInSeconds, \PDO::PARAM_STR);
        
        $pdos->execute();
        
        return (int) $pdos->fetch(\PDO::FETCH_LAZY)->attempts;
    }
    
    /**
     * Return how many login attempts did with the same session in specified time.
     *
     * @param string $ipAddress     Ip address
     * @param int    $timeInSeconds Attempts in the last specified seconds
     */
    public function fetchAttemptsWithSameIp(string $ipAddress, int $timeInSeconds) : int
    {
        $pdos = $this->dBase->prepare('SELECT count(ip) as attempts FROM login_attempt WHERE ip = :ip AND date_time > (now() - :time)');

        $pdos->bindParam(':ip', $ipAddress, \PDO::PARAM_STR);
        $pdos->bindParam(':time', $timeInSeconds, \PDO::PARAM_STR);
        
        $pdos->execute();
        
        return (int) $pdos->fetch(\PDO::FETCH_LAZY)->attempts;
    }
    
    /**
     * Remove old login attempts
     *
     * @param int $timeInSeconds
     */
    public function deleteOldLoginAttempts(int $timeInSeconds) : bool
    {
        $pdos = $this->dBase->prepare('DELETE FROM login_attempt WHERE date_time < (now() - :time)');

        $pdos->bindParam(':time', $timeInSeconds, \PDO::PARAM_STR);
        
        $pdos->execute();
        
        return true;
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
            $pdos = $this->dBase->prepare('INSERT INTO login_attempt (user_name, session_id, ip, date_time) VALUES (:user_name, :session_id, :ip, :date_time)');

            $pdos->bindParam(':user_name', $loginAttempt->userName, \PDO::PARAM_STR);
            $pdos->bindParam(':session_id', $loginAttempt->sessionId, \PDO::PARAM_STR);
            $pdos->bindParam(':ip', $loginAttempt->ipAddress, \PDO::PARAM_STR);
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
            $pdos = $this->dBase->prepare('UPDATE login_attempt SET user_name = :user_name,  session_id = :session_id, ip = :ip,  date_time = :date_time WHERE login_attempt_id = :login_attempt_id');

            $objId = $loginAttempt->getId();

            $pdos->bindParam(':login_attempt_id', $objId, \PDO::PARAM_INT);

            $pdos->bindParam(':user_name', $loginAttempt->userName, \PDO::PARAM_STR);
            $pdos->bindParam(':session_id', $loginAttempt->sessionId, \PDO::PARAM_STR);
            $pdos->bindParam(':ip', $loginAttempt->ipAddress, \PDO::PARAM_STR);
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
        
        $this->checkValidDomainObject($user);

        try {
            $objId = $user->getId();
            $pdos = $this->dBase->prepare('DELETE FROM login_attempt WHERE login_attempt_id = :login_attempt_id');
            $pdos->bindParam(':login_attempt_id', $objId, \PDO::PARAM_INT);
            $pdos->execute();
        } catch (\Exception $e) {
            echo 'Mapper exception: ', $e->getMessage(), "\n";
        }
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
