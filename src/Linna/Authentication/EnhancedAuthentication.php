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
use Linna\Shared\ClassOptionsTrait;

/**
 * Extend basic user authentication system with more security checks.
 */
class EnhancedAuthentication extends Authentication
{
    use ClassOptionsTrait;

    /**
     * @var array An associative array containing options
     */
    protected $options = [
        'maxAttemptsForUserName' => 5,
        'maxAttemptsForSessionId' => 10,
        'maxAttemptsForIpAddress' => 20,
        'maxAttemptsForSecond' => 40,
        'banTimeInSeconds' => 900 //15 minutes
    ];

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
        //set options
        $this->setOptions($options);
    }

    /**
     * Return how many attemps are left for incorrect password.
     *
     * @param string $userName
     *
     * @return int
     */
    public function getAttemptsLeftWithSameUser(string $userName): int
    {
        $attemptsLeft = $this->options['maxAttemptsForUserName'] - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameUser($userName, $this->options['banTimeInSeconds']);

        //casting to int second param for avoid strange things with
        //max return value
        //http://php.net/manual/en/function.max.php
        return max(0, (int) $attemptsLeft);
    }

    /**
     * Return how many attemps are left for same session id.
     *
     * @param string $sessionId
     *
     * @return int
     */
    public function getAttemptsLeftWithSameSession(string $sessionId): int
    {
        $attemptsLeft = $this->options['maxAttemptsForSessionId'] - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameSession($sessionId, $this->options['banTimeInSeconds']);

        return max(0, (int) $attemptsLeft);
    }

    /**
     * Return how many attemps are left for same ip.
     *
     * @param string $ipAddress
     *
     * @return int
     */
    public function getAttemptsLeftWithSameIp(string $ipAddress): int
    {
        $attemptsLeft = $this->options['maxAttemptsForIpAddress'] - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameIp($ipAddress, $this->options['banTimeInSeconds']);

        return max(0, (int) $attemptsLeft);
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
