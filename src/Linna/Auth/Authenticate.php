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
 * This class provide methods for authenticate a user.
 * <br /><br />
 * Utilize
 *
 * Utilize for check login
 * <pre><code class="php">$auth = new Authenticate();
 *
 * if ($auth->logged === true) {
 *     //do actions
 * }
 * </code></pre>
 * 
 * Utilize for logout
 */
class Authenticate
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
     * __construct.
     * 
     * Class constructor.
     *
     * @param Session  $session  Session class instance.
     * @param Password $password Password class instance.
     */
    public function __construct(Session $session, Password $password)
    {
        $this->password = $password;
        $this->sessionInstance = $session;
        $this->logged = $this->refresh();
    }

    /**
     * login.
     * 
     * Try to attemp login for the informations passed by param.
     *
     * <pre><code class="php">$user = ''; //user from login page form
     * $password = ''; //password from login page form
     *
     * $storedUser = ''; //user from stored user informations
     * $storedPassword = ''; //password hash from stored user informations
     * $storedId = ''; //user id from stored user informations
     *
     * $auth = new Authenticate();
     * $auth->login($user, $password, $storedUser, $storedPassword, $storedId);
     *
     * //other operation after login
     * </code></pre>
     *
     * @param string $userName
     * @param string $password
     * @param string $storedUserName
     * @param string $storedPassword
     * @param int    $storedId
     *
     * @return bool
     */
    public function login(string $userName, string $password, string $storedUserName = '', string $storedPassword = '', int $storedId = 0): bool
    {
        if ($this->theLoginChecksFail($userName, $password, $storedUserName, $storedPassword)) {
            return false;
        }

        //write valid login on session
        $this->sessionInstance->loginTime = time();
        $this->sessionInstance->login = [
            'login'     => true,
            'user_id'   => $storedId,
            'user_name' => $storedUserName,
        ];

        //regenerate session id
        $this->sessionInstance->regenerate();
        $this->logged = true;

        return true;
    }

    /**
     * theLoginChecksFail.
     * 
     * Check if the login fail.
     *
     * @param string $userName
     * @param string $password
     * @param string $storedUserName
     * @param string $storedPassword
     *
     * @return bool
     */
    private function theLoginChecksFail(string $userName, string $password, string $storedUserName, string $storedPassword) : bool
    {
        //check user presence
        if ($userName !== $storedUserName) {
            return true;
        }

        //if password doesn't match return false
        if (!$this->password->verify($password, $storedPassword)) {
            return true;
        }

        return false;
    }

    /**
     * logout.
     *
     * Do logout and delete login information from session.
     * <pre><code class="php">$auth = new Authenticate();
     * $auth->logout();
     * </code></pre>
     *
     * @return bool
     */
    public function logout(): bool
    {
        //remove login data from session
        unset($this->sessionInstance->login, $this->sessionInstance->loginTime);

        //regenerate session id
        $this->sessionInstance->regenerate();
        $this->logged = false;

        return true;
    }

    /**
     * refresh.
     * 
     * Check if user is logged, get login data from session and update it.
     *
     * @return bool
     */
    private function refresh(): bool
    {
        //check for login data on in current session
        if (!isset($this->sessionInstance->login)) {
            return false;
        }

        //take time
        $time = time();

        //check if login expired
        if (($this->sessionInstance->loginTime + $this->sessionInstance->expire) < $time) {
            return false;
        }

        //update login data
        $this->sessionInstance->loginTime = $time;
        $this->data = $this->sessionInstance->login;

        return true;
    }
}
