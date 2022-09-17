<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use Linna\DataMapper\MapperInterface;

/**
 * This interface declare methods the concrete Enhanced Authentication Mapper
 * must implement.
 */
interface EnhancedAuthenticationMapperInterface extends MapperInterface
{
    /**
     * Return how many login attempts did with the same user in specified time.
     *
     * @param string $userName      User name.
     * @param int    $timeInSeconds Attempts in the last specified seconds.
     *
     * @return int The number of attempts.
     */
    public function fetchAttemptsWithSameUser(string $userName, int $timeInSeconds): int;

    /**
     * Return how many login attempts did with the same session id in specified
     * time.
     *
     * @param string $sessionId     Session id.
     * @param int    $timeInSeconds Attempts in the last specified seconds
     *
     * @return int The number of attempts.
     */
    public function fetchAttemptsWithSameSession(string $sessionId, int $timeInSeconds): int;

    /**
     * Return how many login attempts did with the same ip address in specified
     * time.
     *
     * @param string $ipAddress     Ip address.
     * @param int    $timeInSeconds Attempts in the last specified seconds.
     *
     * @return int The number of attempts.
     */
    public function fetchAttemptsWithSameIp(string $ipAddress, int $timeInSeconds): int;

    /**
     * Remove old login attempts.
     *
     * @param int $timeInSeconds The time in seconds before which the login
     *                           attemts are removed.
     *
     * @return bool True if deletion is done without errors, false otherwise.
     */
    public function deleteOldLoginAttempts(int $timeInSeconds): bool;
}
