<?php

/**
 * Leviu.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace Leviu\Auth;

use Leviu\Session\Session;
use Leviu\Auth\Password;

/**
 * Login
 * - Class a for autenticate users :).
 *
 * Utilize for do login
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
 * Utilize for under login actions
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
     * @var int Current user id
     */
    public $userId = 0;

    /**
     * @var string Current user name
     */
    public $userName = '';

    /**
     * $login->isLogged can be check if user is logged.
     *
     * @var bool User login status
     */
    public $isLogged = false;

    /**
     * @var int Numeber of seconds before login will considered invalid
     */
    private $loginExpire = 1800;

    /**
     * Login constructor.
     * 
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->isLogged = $this->check();
    }

   /**
    * Login.
    * 
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
       $p = new Password;
       
       if ($user === $storedUser) {
           //if (password_verify($password, $storedPassword)) {
           if ($p->verify($password, $storedPassword)) {
               $this->userId = $storedId;
               $this->userName = $storedUser;

               $this->isLogged = true;

               $_SESSION['login'] =
                    [
                        'user_id' => $storedId,
                        'user_name' => $storedUser,
                        'time' => time(),
                    ];

               Session::getInstance()->regenerate();

               return true;
           }
       }

       return false;
   }

    /**
     * Logout.
     * 
     * For do logout, delete login information from session
     * 
     * @return bool
     *
     * @since 0.1.0
     */
    public function logout()
    {
        unset($_SESSION['login']);

        Session::getInstance()->regenerate();

        return true;
    }

    /**
     * Check.
     * 
     * For check if user is logged, get login data from session and update it
     * 
     * @return bool
     *
     * @since 0.1.0
     */
    private function check()
    {
        if (isset($_SESSION['login'])) {
            $loginData = $_SESSION['login'];

            $time = time();

            if ($loginData['time'] > ($time - $this->loginExpire)) {
                $loginData['time'] = $time;

                $this->userId = $loginData['user_id'];
                $this->userName = $loginData['user_name'];

                $_SESSION['login'] = $loginData;

                return true;
            }
        }

        return false;
    }
}
