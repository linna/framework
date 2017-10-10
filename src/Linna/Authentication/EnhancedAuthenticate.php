<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
    protected $options = [];
    
    /**
     * Class Constructor
     *
     * @param Session $session
     * @param Password $password
     * @param array $options
     */
    public function __construct(Session $session, Password $password, array $options = [])
    {
        parent::__construct($session, $password);
        
        //set options
        $this->setOptions($options);
    }
    
    /**
     * Try to attempt login with the informations passed by param.
     *
     * @param string $userName
     * @param string $password
     * @param string $storedUserName
     * @param string $storedPassword
     * @param int $storedId
     *
     * @return boolean
     */
    public function login(string $userName, string $password, string $storedUserName = '', string $storedPassword = '', int $storedId = 0): bool
    {
        if (parent::login($userName, $password, $storedUserName, $storedPassword, $storedId)) {
            return true;
        }
    }
    
    /**
     * Check if an account is locked after too much failed.
     *
     * @param string $userName
     *
     * @return bool
     */
    public function IsAccountLocked(string $userName) : bool
    {
        return true;
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
        return 0;
    }
    
    /**
     * Return how many attemps are left for same ip.
     *
     * @param string $ip
     *
     * @return int
     */
    public function getAttemptsLeftWithSameIp(string $ip) : int
    {
        return 0;
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
        return 0;
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
        return true;
    }
    
    /**
     * Check if an ip address is banned from do login.
     *
     * @param string $ip
     *
     * @return bool
     */
    public function isIpBanned(string $ip) : bool
    {
        return true;
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
        return true;
    }
}
