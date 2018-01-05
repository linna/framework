<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

use Linna\DataMapper\MapperInterface;

/**
 * Contain methods required from concrete Enhanced Authenticate Mapper.
 */
interface EnhancedAuthenticateMapperInterface extends MapperInterface
{
    /**
     * Return how many login attempts did with the same user in specified time.
     *
     * @param string $userName      User name
     * @param int    $timeInSeconds Attempts in the last specified seconds
     */
    public function fetchAttemptsWithSameUser(string $userName, int $timeInSeconds) : int;
    
    /**
     * Return how many login attempts did with the same session in specified time.
     *
     * @param string $sessionId     Session id
     * @param int    $timeInSeconds Attempts in the last specified seconds
     */
    public function fetchAttemptsWithSameSession(string $sessionId, int $timeInSeconds) : int;
    
    /**
     * Return how many login attempts did with the same session in specified time.
     *
     * @param string $ipAddress     Ip address
     * @param int    $timeInSeconds Attempts in the last specified seconds
     */
    public function fetchAttemptsWithSameIp(string $ipAddress, int $timeInSeconds) : int;
    
    /**
     * Remove old login attempts
     *
     * @param int $timeInSeconds
     */
    public function deleteOldLoginAttempts(int $timeInSeconds) : bool;
}
