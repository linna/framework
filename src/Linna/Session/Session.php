<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

use Linna\Shared\ArrayAccessTrait;
use Linna\Shared\PropertyAccessTrait;
use ArrayAccess;
use SessionHandlerInterface;

/**
 * Manage session lifetime and session data.
 */
class Session implements ArrayAccess
{
    use PropertyAccessTrait;
    use ArrayAccessTrait;

    /** @var array<mixed> Session data reference property */
    private array $data = [];

    /** @var string Session id */
    private string $id;

    /** @var int session_status function result */
    private int $status;

    /**
     * Class Constructor.
     *
     * @param string $name           The name for the current session.
     * @param int    $expire         The time in seconds for which the cookie session will be valid.
     * @param string $cookieDomain   Cookie domain, for example 'www.linna.tools'. To make cookies visible on all subdomains then the domain must be prefixed with a dot like '.linna.tools'.
     * @param string $cookiePath     The path on the domain where the cookie will work. Use a single slash ('/') for all paths on the domain.
     * @param bool   $cookieSecure   Decide if the cookie will be sent only over secure connections.
     * @param bool   $cookieHttpOnly Make the cookie only available for http requests, it should meke the cookie not accessible by scripting languages api.
     * @param string $cookieSameSite Make the cookie only available for same site requests, the browser should not send the cookie for cross-site requests.
     */
    public function __construct(
        /** @var string The name for the current session. */
        private string $name = 'linna_session',

        /** @var int The time in seconds for which the cookie session will be valid.*/
        private int $expire = 1800,

        /** @var string Cookie domain, for example 'www.linna.tools'. To make cookies visible on all subdomains then the domain must be prefixed with a dot like '.linna.tools'. */
        private string $cookieDomain = '',

        /** @var string The path on the domain where the cookie will work. Use a single slash ('/') for all paths on the domain. */
        private string $cookiePath = '/',

        /** @var bool Decide if the cookie will be sent only over secure connections. */
        private bool $cookieSecure = false,

        /** @var bool Make the cookie only available for http requests, it should meke the cookie not accessible by scripting languages api. */
        private bool $cookieHttpOnly = true,

        /** @var string Make the cookie only available for same site requests, the browser should not send the cookie for cross-site requests. */
        private string $cookieSameSite = 'lax',
    ) {
        $this->status = \session_status();
    }

    /**
     * Get current session name.
     *
     * @return string The current session name.
     */
    public function getSessionName(): string
    {
        return $this->name;
    }

    /**
     * Get current session id.
     *
     * @return string The current session id.
     */
    public function getSessionId(): string
    {
        return $this->id;
    }

    /**
     * Get session status.
     *
     * @return int The current session status.
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Regenerate session_id without double cookie problem.
     *
     * @return void
     */
    public function regenerate(): void
    {
        //regenerate session id
        \session_regenerate_id();

        //store new session data
        $this->setSessionData(\time());
    }

    /**
     * Start session.
     *
     * @return void
     */
    public function start(): void
    {
        if (\session_status() !== PHP_SESSION_ACTIVE) {
            //prepare the session start
            \session_name($this->name);

            //start session
            \session_start([
                'cookie_path'      => $this->cookiePath,
                'cookie_domain'    => $this->cookieDomain,
                'cookie_lifetime'  => $this->expire,
                'cookie_secure'    => $this->cookieSecure,
                'cookie_httponly'  => $this->cookieHttpOnly,
                'cookie_samesite'  => $this->cookieSameSite
            ]);

            //link session super global to $data property
            $this->data = &$_SESSION;
        }

        //refresh session
        $this->refresh();
    }

    /**
     * Refresh session.
     *
     * @return void
     */
    private function refresh(): void
    {
        $time = \time();

        if (isset($this->data['time']) && $this->data['time'] <= ($time - $this->expire)) {
            //delete session data
            $this->data = [];

            //regenerate session
            $this->regenerate();
        }

        $this->setSessionData($time);

        //it fixs the behavior of session that die because it does not refresh
        //expiration time, also if present user interaction, with browser.

        //PHP 7.2 version
        /*\setcookie($this->name, $this->id,
            $this->expire, //($this->expire > 0) ? time() + $this->expire : 0, //expire
            //($time + $this->expire),    //expire
            $this->cookiePath,          //path
            $this->cookieDomain,        //domain
            $this->cookieSecure,        //secure
            $this->cookieHttpOnly       //http only
        );*/

        //PHP 7.3 version
        //https://www.php.net/manual/en/migration73.other-changes.php
        \setcookie($this->name, $this->id, [
            'path'      => $this->cookiePath,
            'domain'    => $this->cookieDomain,
            'expires'   => $time + $this->expire,
            'secure'    => $this->cookieSecure,
            'httponly'  => $this->cookieHttpOnly,
            'samesite'  => $this->cookieSameSite
        ]);
    }

    /**
     * Set Session Data.
     *
     * @param int $time The time on which the session was created.
     *
     * @return void
     */
    private function setSessionData(int $time): void
    {
        $this->id = \session_id();
        $this->data['time'] = $time;
        $this->data['expire'] = $this->expire;
        $this->status = \session_status();
    }

    /**
     * Set session handler for new instance.
     *
     * @param SessionHandlerInterface $handler The handler to use to store session data.
     *
     * @return void
     */
    public function setSessionHandler(SessionHandlerInterface $handler): void
    {
        //setting a different save handler if passed
        \session_set_save_handler($handler, true);
    }

    /**
     * Destroy session.
     *
     * @return void
     */
    public function destroy(): void
    {
        //delete session data
        $this->data = [];
        $this->id = '';

        //destroy cookie
        \setcookie($this->name, 'NothingToSeeHere.', \time());

        //call session destroy
        \session_destroy();

        //update status
        $this->status = \session_status();
    }

    /**
     * Write session data and end session.
     *
     * @return void
     */
    public function commit(): void
    {
        \session_write_close();

        //update status
        $this->status = \session_status();
    }

    /**
     * Get an entry from the session storage.
     *
     * @param string $id The identifier of the entry to look for.
     *
     * @return mixed The entry for which we are looking for, null otherwise.
     */
    public function get(string $id): mixed
    {
        if (isset($this->data[$id])) {
            return $this->data[$id];
        }

        return null;
    }

    /**
     * Check for an entry into session storage.
     *
     * @param string $id The identifier of the entry to look for.
     *
     * @return bool True if the class has the property, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->data[$id]);
    }

    /**
     * Set an entry into session storage.
     *
     * @param string $id    The identifier for the value which will be stored.
     * @param mixed  $value The value which will be stored.
     *
     * @return void
     */
    public function set(string $id, mixed $value): void
    {
        $this->data[$id] = $value;
    }

    /**
     * Delete an entry from session storage.
     *
     * @param string $id The identifier for the entry which will be deleted.
     *
     * @return void
     */
    public function delete(string $id): void
    {
        unset($this->data[$id]);
    }
}
