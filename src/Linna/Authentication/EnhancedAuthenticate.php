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
class EnhancedAuthenticate extends Authenticate
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
     * @var EnhancedAuthenticateMapperInterface Enhanced Authenticate Mapper
     */
    private $enhancedAuthenticateMapper;
    
    /**
     * Class Constructor
     *
     * @param Session $session
     * @param Password $password
     * @param EnhancedAuthenticateMapperInterface $enhancedAuthenticateMapper
     * @param array $options
     */
    public function __construct(
            Session $session,
            Password $password,
            EnhancedAuthenticateMapperInterface $enhancedAuthenticateMapper,
            array $options = []
        ) {
        parent::__construct($session, $password);
        
        $this->enhancedAuthenticateMapper = $enhancedAuthenticateMapper;
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
    public function getAttemptsLeftWithSameUser(string $userName) : int
    {
        $attemptsLeft = $this->options['maxAttemptsForUserName'] - $this->enhancedAuthenticateMapper->fetchAttemptsWithSameUser($userName, $this->options['banTimeInSeconds']);

        return max(0, $attemptsLeft);
    }
    
    /**
     * Return how many attemps are left for same session id.
     *
     * @param string $sessionId
     *
     * @return int
     */
    public function getAttemptsLeftWithSameSession(string $sessionId) : int
    {
        $attemptsLeft = $this->options['maxAttemptsForSessionId'] - $this->enhancedAuthenticateMapper->fetchAttemptsWithSameSession($sessionId, $this->options['banTimeInSeconds']);

        return max(0, $attemptsLeft);
    }
    
    /**
     * Return how many attemps are left for same ip.
     *
     * @param string $ipAddress
     *
     * @return int
     */
    public function getAttemptsLeftWithSameIp(string $ipAddress) : int
    {
        $attemptsLeft = $this->options['maxAttemptsForIpAddress'] - $this->enhancedAuthenticateMapper->fetchAttemptsWithSameIp($ipAddress, $this->options['banTimeInSeconds']);

        return max(0, $attemptsLeft);
    }
    
    /**
     * Check if an user is banned from do login.
     *
     * @param string $userName
     *
     * @return bool
     */
    public function isUserBanned(string $userName) : bool
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
    public function isSessionBanned(string $sessionId) : bool
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
    public function isIpBanned(string $ipAddress) : bool
    {
        return !$this->getAttemptsLeftWithSameIp($ipAddress);
    }
}
