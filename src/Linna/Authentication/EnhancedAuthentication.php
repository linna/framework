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

use Linna\Session\Session;

/**
 * Extends the basic user authentication system with more security checks.
 */
class EnhancedAuthentication extends Authentication
{
    /**
     * Class Constructor.
     *
     * @param Session                               $session                 Session class instance.
     * @param Password                              $password                Password class instance.
     * @param EnhancedAuthenticationMapperInterface $mapper                  Implementation of EnhancedAuthenticationMapper.
     * @param int                                   $maxAttemptsForUserName  The number of maximum attempts for the user name.
     * @param int                                   $maxAttemptsForSessionId The number of maximum attempts for the session id.
     * @param int                                   $maxAttemptsForIpAddress The number of maximum attempts for the ip address.
     * @param int                                   $maxAttemptsForSecond    The number of maximum attempts in a second.
     * @param int                                   $banTimeInSeconds        The number of seconds fo a ban.
     */
    public function __construct(
        Session $session,
        Password $password,
        private EnhancedAuthenticationMapperInterface $mapper,
        // options
        protected int $maxAttemptsForUserName = 5,
        protected int $maxAttemptsForSessionId = 10,
        protected int $maxAttemptsForIpAddress = 20,
        protected int $maxAttemptsForSecond = 40,
        protected int $banTimeInSeconds = 900
    ) {
        parent::__construct($session, $password);
    }

    /**
     * Return how many attempts are left for a user.
     *
     * When the result of this method reach zero, it means that should be the
     * time to lock the user account if the user name is a valid application
     *  user.
     *
     * @param string $userName User for which retrieve login attempts.
     *
     * @return int The number of attempts with same user.
     */
    public function getAttemptsLeftWithSameUser(string $userName): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForUserName) - $this->mapper->fetchAttemptsWithSameUser($userName, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Return how many attempts are left for same session id.
     *
     * @param string $sessionId Session id for which retrieve login attempts.
     *
     * @return int Number of attempts with same session.
     */
    public function getAttemptsLeftWithSameSession(string $sessionId): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForSessionId) - $this->mapper->fetchAttemptsWithSameSession($sessionId, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Return how many attempts are left for same ip.
     *
     * When the result of this method reach zero, it means that should be the
     *  time to ban the ip addres used to attempt to login.
     *
     * @param string $ipAddress Ip address for which retrieve login attempts.
     *
     * @return int Number of attempts with same ip.
     */
    public function getAttemptsLeftWithSameIp(string $ipAddress): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForIpAddress) - $this->mapper->fetchAttemptsWithSameIp($ipAddress, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Check if an user is banned from do login.
     *
     * @param string $userName User for which check if is banned.
     *
     * @return bool True if the user is banned, false otherwise.
     */
    public function isUserBanned(string $userName): bool
    {
        return !$this->getAttemptsLeftWithSameUser($userName);
    }

    /**
     * Check if a session id is banned from do login.
     *
     * @param string $sessionId Session id for which check if is banned.
     *
     * @return bool True if the session id is banned, false otherwise.
     */
    public function isSessionBanned(string $sessionId): bool
    {
        return !$this->getAttemptsLeftWithSameSession($sessionId);
    }

    /**
     * Check if an ip address is banned from do login.
     *
     * @param string $ipAddress Ip address for which check if is banned.
     *
     * @return bool True if the ip address is banned, false otherwise.
     */
    public function isIpBanned(string $ipAddress): bool
    {
        return !$this->getAttemptsLeftWithSameIp($ipAddress);
    }
}
