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
 * Provide methods for manage basic user authentication system. Checks for
 * correct login cover only the user name and the password.
 */
class Authentication
{
    /**
     * @var array Login status.
     */
    private $data = ['user_name'=>''];

    /**
     * @var bool Indicate login status, true or false.
     */
    private $logged = false;

    /**
     * @var Password Password class.
     */
    private $password;

    /**
     * @var Session Session class.
     */
    protected $session;

    /**
     * Class constructor.
     * <pre><code class="php">use Linna\Session\Session;
     * use Linna\Auth\Password;
     *
     * $session = new Session();
     * $password = new Password();
     *
     * $auth = new Authentication($session, $password);
     * </code></pre>
     *
     * @param Session  $session  Session class instance.
     * @param Password $password Password class instance.
     */
    public function __construct(Session $session, Password $password)
    {
        $this->password = $password;
        $this->session = $session;
        $this->logged = $this->refresh();
    }

    /**
     * Utilize this method for check if an user in the current session,
     * is currently logged in.
     * <pre><code class="php">$auth = new Authentication($session, $password);
     *
     * if ($auth->isLogged()) {
     *     //do actions
     * }
     * </code></pre>
     *
     * @return bool True if logged false if no.
     */
    public function isLogged(): bool
    {
        return $this->logged;
    }

    /**
     * Opposite to isLogged() method.
     *
     * Utilize this method for check if an user in the current session,
     * is currently not logged in.
     * <pre><code class="php">$auth = new Authentication($session, $password);
     *
     * if ($auth->isNotLogged()) {
     *     //redirect or other action
     * }
     *
     * //do actions
     * </code></pre>
     *
     * @return bool True if not logged false if logged.
     */
    public function isNotLogged(): bool
    {
        return !$this->logged;
    }

    /**
     * Return array containing login data.
     * <pre><code class="php">$auth = new Authentication($session, $password);
     *
     * $data = $auth->getLoginData();
     *
     * //var_dump result
     * //after session start and login, session data appear like below array:
     * //[
     * //    'time' => 1479641396
     * //    'expire' => 1800
     * //    'loginTime' => 1479641395
     * //    'login' => [
     * //        'login' => true
     * //        'user_id' => 1
     * //        'user_name' => 'root'
     * //    ]
     * //]
     * var_dump($data);
     * </code></pre>
     *
     * @return array Login data.
     */
    public function getLoginData(): array
    {
        return $this->data;
    }

    /**
     * Try to attemp login with the informations passed by param.
     *
     * <pre><code class="php">$user = ''; //user from login page form
     * $password = ''; //password from login page form
     *
     * $storedUser = ''; //user from stored informations
     * $storedPassword = ''; //password hash from stored informations
     * $storedId = ''; //user id from stored informations
     *
     * $auth = new Authentication($session, $password);
     * $auth->login($user, $password, $storedUser, $storedPassword, $storedId);
     *
     * //other operation after login
     * </code></pre>
     *
     * @param string $userName       User name from browser input.
     * @param string $password       Password from browser input.
     * @param string $storedUserName User name from persistent storage.
     * @param string $storedPassword Password from persistent storage.
     * @param int    $storedId       User id from persistent storage.
     *
     * @return bool True for successful login, false if login fails.
     */
    public function login(string $userName, string $password, string $storedUserName = '', string $storedPassword = '', int $storedId): bool
    {
        if ($userName === $storedUserName && $this->password->verify($password, $storedPassword)) {
            //write valid login on session
            $this->session->loginTime = time();
            $this->session->login = [
                'login'     => true,
                'user_id'   => $storedId,
                'user_name' => $storedUserName,
            ];

            //update login data
            $this->data = $this->session->login;

            //regenerate session id
            $this->session->regenerate();
            $this->logged = true;

            return true;
        }

        return false;
    }

    /**
     * Do logout and delete login information from session.
     * <pre><code class="php">$auth = new Authentication($session, $password);
     * $auth->logout();
     * </code></pre>
     *
     * @return bool True if logout is done.
     */
    public function logout(): bool
    {
        //remove login data from session
        unset($this->session->login, $this->session->loginTime);

        //regenerate session id
        $this->session->regenerate();
        $this->logged = false;

        return true;
    }

    /**
     * Check if user is logged, get login data from session and update it.
     *
     * @return bool True if refresh is done false if no.
     */
    private function refresh(): bool
    {
        //check for login data on in current session
        if (empty($this->session->login)) {
            return false;
        }

        //take time
        $time = time();

        //check if login expired
        if (($this->session->loginTime + $this->session->expire) < $time) {
            return false;
        }

        //update login data
        $this->session->loginTime = $time;
        $this->data = $this->session->login;

        return true;
    }
}
