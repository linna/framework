<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use Linna\Session\Session;

/**
 * Provide methods to manage basic user authentication system.
 *
 * <p>The check for correct login covers only the user name and the password.</p>
 */
class Authentication
{
    /** @var array<mixed> Login status. */
    private array $data = ['user_name' => '', 'user_id' => ''];

    /** @var bool Indicate login status, true or false. */
    private bool $logged = false;

    /**
     * Class constructor.
     *
     * @param Session  $session  Session class instance.
     * @param Password $password Password class instance.
     */
    public function __construct(
        protected Session $session,
        private Password $password
    ) {
        $this->logged = $this->refresh();
    }

    /**
     * Checks if the user in the current session, is currently logged in.
     *
     * @return bool True if logged, false otherwise.
     */
    public function isLogged(): bool
    {
        return $this->logged;
    }

    /**
     * Opposite to <code>isLogged()</code> method.
     *
     * <p>Utilize this method to check if the user in the current session,
     * isn't logged in.</p>
     *
     * @return bool True if not logged, false otherwise.
     */
    public function isNotLogged(): bool
    {
        return !$this->logged;
    }

    /**
     * Return an array containing login data.
     *
     * @return array<mixed> Login data.
     */
    public function getLoginData(): array
    {
        return $this->data;
    }

    /**
     * Try to do user login using the information passed by param.
     *
     * <p>This method shoud be tested for time attacks.</p>
     *
     * @param string     $userName       User name from browser input.
     * @param string     $password       Password from browser input.
     * @param string     $storedUserName User name from persistent storage.
     * @param string     $storedPassword Password hash from persistent storage.
     * @param int|string $storedId       User id from persistent storage.
     *
     * @return bool True for successful login, false otherwise.
     */
    public function login(string $userName, string $password, string $storedUserName, string $storedPassword, int|string $storedId): bool
    {
        if (\hash_equals($userName, $storedUserName) && $this->password->verify($password, $storedPassword)) {
            //write valid login on session
            $this->session->loginTime = \time();
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
     * Do logout and delete user login information from session.
     *
     * @return bool Always true.
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
     * @return bool True if refresh is done, false otherwise.
     */
    private function refresh(): bool
    {
        //check for login data on in current session
        if (empty($this->session->login)) {
            return false;
        }

        //take time
        $time = \time();

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
