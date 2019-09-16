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

use Linna\Session\Session;

/**
 * Extend basic user authentication system with more security checks.
 */
class EnhancedAuthentication extends Authentication
{
    /**
     * @var int Max attempts for user name.
     */
    protected $maxAttemptsForUserName = 5;

    /**
     * @var int Max attempts for session id.
     */
    protected $maxAttemptsForSessionId = 10;

    /**
     * @var int Max attempts for ip address.
     */
    protected $maxAttemptsForIpAddress = 20;

    /**
     * @var int Max attempts for second.
     */
    protected $maxAttemptsForSecond = 40;

    /**
     * @var int Ban time in seconds.
     */
    protected $banTimeInSeconds = 900;

    /**
     * @var EnhancedAuthenticationMapperInterface Enhanced Authentication Mapper
     */
    private $enhancedAuthenticationMapper;

    /**
     * Class Constructor
     *
     * @param Session                               $session
     * @param Password                              $password
     * @param EnhancedAuthenticationMapperInterface $enhancedAuthenticationMapper
     * @param array                                 $options
     */
    public function __construct(
        Session $session,
        Password $password,
        EnhancedAuthenticationMapperInterface $enhancedAuthenticationMapper,
        array $options = []
    ) {
        parent::__construct($session, $password);

        $this->enhancedAuthenticationMapper = $enhancedAuthenticationMapper;

        [
            'maxAttemptsForUserName'  => $this->maxAttemptsForUserName,
            'maxAttemptsForSessionId' => $this->maxAttemptsForSessionId,
            'maxAttemptsForIpAddress' => $this->maxAttemptsForIpAddress,
            'maxAttemptsForSecond'    => $this->maxAttemptsForSecond,
            'banTimeInSeconds'        => $this->banTimeInSeconds
        ] = \array_replace_recursive([
            'maxAttemptsForUserName'  => $this->maxAttemptsForUserName,
            'maxAttemptsForSessionId' => $this->maxAttemptsForSessionId,
            'maxAttemptsForIpAddress' => $this->maxAttemptsForIpAddress,
            'maxAttemptsForSecond'    => $this->maxAttemptsForSecond,
            'banTimeInSeconds'        => $this->banTimeInSeconds
        ], $options);
    }

    /**
     * Return how many attemps are left for incorrect password.
     *
     * @param string $userName
     *
     * @return int Number of attempts with same user.
     */
    public function getAttemptsLeftWithSameUser(string $userName): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForUserName) - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameUser($userName, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Return how many attemps are left for same session id.
     *
     * @param string $sessionId
     *
     * @return int Number of attempts with same session.
     */
    public function getAttemptsLeftWithSameSession(string $sessionId): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForSessionId) - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameSession($sessionId, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Return how many attemps are left for same ip.
     *
     * @param string $ipAddress
     *
     * @return int Number of attempts with same ip.
     */
    public function getAttemptsLeftWithSameIp(string $ipAddress): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForIpAddress) - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameIp($ipAddress, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Check if an user is banned from do login.
     *
     * @param string $userName
     *
     * @return bool
     */
    public function isUserBanned(string $userName): bool
    {
        return !$this->getAttemptsLeftWithSameUser($userName);
    }

    /**
     * Check if a session id is banned from do login.
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function isSessionBanned(string $sessionId): bool
    {
        return !$this->getAttemptsLeftWithSameSession($sessionId);
    }

    /**
     * Check if an ip address is banned from do login.
     *
     * @param string $ipAddress
     *
     * @return bool
     */
    public function isIpBanned(string $ipAddress): bool
    {
        return !$this->getAttemptsLeftWithSameIp($ipAddress);
    }
}
