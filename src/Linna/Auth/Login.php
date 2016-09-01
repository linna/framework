<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Auth;

use Linna\Session\Session;
use Linna\Auth\Password;

/**
 * Class a for autenticate users
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
 *      if ($login->logged === true)
 *      {
 *              //do actions
 *      }
 * 
 * Utilize for logout
 * 
 *      $login = new Login();
 *      $login->logout();
 *      
 */
class Login
{

    /**
     * @var array $data Login status
     */
    public $data = array('user_name'=>'');

    /**
     * @var bool $logged Indicate login status, true or false 
     */
    public $logged = false;
    
    /**
     * @var int $expire Numeber of seconds before login will considered invalid
     */
    private $expire = 1800;
    
    /**
     * @var object $sessionInstance 
     */
    private $sessionInstance;

    /**
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->sessionInstance = Session::getInstance();
        $this->logged = $this->refresh();
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

        $this->sessionInstance->loginTime = time();
        $this->sessionInstance->login = [
            'login' => true,
            'user_id' => $storedId,
            'user_name' => $storedUser
        ];

        
        $this->sessionInstance->regenerate();

        return true;
    }

    /**
     * For do logout, delete login information from session
     * 
     * @return bool
     */
    public function logout()
    {
        unset($this->sessionInstance->login, $this->sessionInstance->loginTime);
        
        $this->sessionInstance->regenerate();

        return true;
    }

    /**
     * Check if user is logged, get login data from session and update it
     * 
     * @return bool
     */
    private function refresh()
    {
        if (!isset($this->sessionInstance->login)) {
            return false;
        }

        $time = time();

        if ($this->sessionInstance->loginTime < ($time - $this->expire)) {
            return false;
        }
        
        $this->sessionInstance->loginTime = $time;

        return true;
    }
}
