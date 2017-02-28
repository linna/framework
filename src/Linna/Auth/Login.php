<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

use Linna\Session\Session;

/**
 * Class a for autenticate users.
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
 */
class Login
{
    /**
     * @var array Login status
     */
    public $data = ['user_name'=>''];

    /**
     * @var bool Indicate login status, true or false
     */
    public $logged = false;

    /**
     * @var Session Session class
     */
    private $sessionInstance;

    /**
     * @var Password Password class
     */
    private $password;

    /**
     * Constructor.
     *
     * @param Session  $session  Instance of session object
     * @param Password $password Instance of password object
     */
    public function __construct(Session $session, Password $password)
    {
        $this->password = $password;
        $this->sessionInstance = $session;
        $this->logged = $this->refresh();
    }

    /**
     * Try to log the user passed by param, return true if ok else false.
     *
     * @param string $user
     * @param string $password
     * @param string $storedUser
     * @param string $storedPassword
     * @param int    $storedId
     *
     * @return bool
     */
    public function login(string $user, string $password, string $storedUser = '', string $storedPassword = '', int $storedId = 0): bool
    {
        if ($user !== $storedUser) {
            return false;
        }

        if (!$this->password->verify($password, $storedPassword)) {
            return false;
        }

        $this->sessionInstance->loginTime = time();
        $this->sessionInstance->login = [
            'login'     => true,
            'user_id'   => $storedId,
            'user_name' => $storedUser,
        ];

        $this->sessionInstance->regenerate();
        $this->logged = true;

        return true;
    }

    /**
     * For do logout, delete login information from session.
     *
     * @return bool
     */
    public function logout(): bool
    {
        unset($this->sessionInstance->login, $this->sessionInstance->loginTime);

        $this->sessionInstance->regenerate();
        $this->logged = false;

        return true;
    }

    /**
     * Check if user is logged, get login data from session and update it.
     *
     * @return bool
     */
    private function refresh(): bool
    {
        if (!isset($this->sessionInstance->login)) {
            return false;
        }

        $time = time();

        if (($this->sessionInstance->loginTime + $this->sessionInstance->expire) < $time) {
            return false;
        }

        $this->sessionInstance->loginTime = $time;
        $this->data = $this->sessionInstance->login;

        return true;
    }
}
