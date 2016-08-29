<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Auth;

use Leviu\Session\Session;
use Leviu\Auth\Password;

/**
 * Class a for autenticate users :).
 *
 * Utilize for login
 * 
 *      <?php
 *      $user = ''; //user from login page form
 *      $password = ''; //password from login page form
 * 
 *      $storedUser = ''; //user from stored user informations
 *      $storedPassword = ''; //password from stored user informations
 *      $storedId = ''; //user id from stored user informations
 * 
 * 
 *      $login = new Login();
 *      $login->login($user, $password, $storedUser, $storedPassword, $storedId);
 *      
 *      //redirect
 * 
 * 
 * Utilize for check login
 * 
 *      <?php
 *      $login = new Login();
 * 
 *      if ($login->isLogged === true)
 *      {
 *              //do actions
 *      }
 * 
 * Utilize for logout
 * 
 *      $login = new Login();
 *      $login->logout();
 *      
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Login
{

    /**
     * @var int $userId Current user id
     */
    public $userId = 0;

    /**
     * @var string $userName Current user name
     */
    public $userName = '';

    /**
     * $login->isLogged can be read for check if user is logged.
     *
     * @var bool $isLogged User login status
     */
    public $isLogged = false;

    /**
     * @var int $loginExpire Numeber of seconds before login will considered invalid
     */
    private $loginExpire = 1800;
    
    private $sessionInstance;

    /**
     * Constructor.
     * 
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->sessionInstance = Session::getInstance();
        $this->isLogged = $this->check();
    }

    /**
     * Try to log user passed by param, return true if ok else false
     * 
     * @param string $user
     * @param string $password
     * @param string $storedUser
     * @param string $storedPassword
     * @param string $storedId
     *
     * @return bool
     *
     * @since 0.1.0
     */
    public function login($user, $password, $storedUser = '', $storedPassword = '', $storedId = 0)
    {
        $pass = new Password;

        if ($user !== $storedUser) {
            return false;
        }

        if (!$pass->verify($password, $storedPassword)) {
            return false;
        }

        $this->userId = $storedId;
        $this->userName = $storedUser;

        $this->isLogged = true;

        $this->sessionInstance->login = [
            'user_id' => $storedId,
            'user_name' => $storedUser,
            'time' => time(),
        ];

        $this->sessionInstance->regenerate();

        return true;
    }

    /**
     * For do logout, delete login information from session
     * 
     * @return bool
     *
     * @since 0.1.0
     */
    public function logout()
    {
        unset($this->sessionInstance->login);

        $this->sessionInstance->regenerate();

        return true;
    }

    /**
     * Check if user is logged, get login data from session and update it
     * 
     * @return bool
     *
     * @since 0.1.0
     */
    private function check()
    {
        if (isset($this->sessionInstance->login)) {
            return false;
        }

        $loginData = $this->sessionInstance->login;

        $time = time();

        if ($loginData['time'] < ($time - $this->loginExpire)) {
            return false;
        }

        $loginData['time'] = $time;

        $this->userId = $loginData['user_id'];
        $this->userName = $loginData['user_name'];

        $this->sessionInstance->login = $loginData;

        return true;
    }
}
