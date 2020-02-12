<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Session;

use ArrayAccess;
use SessionHandlerInterface;

/**
 * Manage session lifetime and session data.
 *
 * @property int   $time      Time of session
 * @property array $login     Login information set by Login class
 * @property int   $loginTime Login time set by Login class
 * @property int   $expire    Login time set for Login class
 */
class Session implements ArrayAccess
{
    use PropertyAccessTrait;
    use ArrayAccessTrait;

    /**
     * @var int Session expire time in seconds.
     */
    private $expire = 1800;

    /**
     * @var string Cookie domain.
     */
    private $cookieDomain = '/';

    /**
     * @var string Coockie path.
     */
    private $cookiePath = '/';

    /**
     * @var bool Cookie transmitted over https only?.
     */
    private $cookieSecure = false;

    /**
     * @var bool Cookie accessible only through http?.
     */
    private $cookieHttpOnly = true;

    /**
     *
     * @var string Cookie same site for cross site requests.
     */
    private $cookieSameSite = 'lax';

    /**
     * @var array Session data reference property
     */
    private $data = [];

    /**
     * @var string Session id
     */
    public $id = '';

    /**
     * @var string Session name
     */
    public $name = 'linna_session';

    /**
     * @var int session_status function result
     */
    public $status = 0;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        [
            'expire'         => $this->expire,
            'name'           => $this->name,
            'cookieDomain'   => $this->cookieDomain,
            'cookiePath'     => $this->cookiePath,
            'cookieSecure'   => $this->cookieSecure,
            'cookieHttpOnly' => $this->cookieHttpOnly
        ] = \array_replace_recursive([
            'expire'         => $this->expire,
            'name'           => $this->name,
            'cookieDomain'   => $this->cookieDomain,
            'cookiePath'     => $this->cookiePath,
            'cookieSecure'   => $this->cookieSecure,
            'cookieHttpOnly' => $this->cookieHttpOnly
        ], $options);

        $this->status = \session_status();
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
                'cookie_lifetime'  => $this->expire,//($this->expire > 0) ? time() + $this->expire : 0,
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

        //it fix the behavior of session that die because it does not refresh
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
     * @param int $time
     *
     * @return void
     */
    private function setSessionData(int $time): void
    {
        $this->id = \session_id();
        $this->data['time'] = $time;
        $this->data['expire'] = $this->expire;
        //$this->data['server'] = $_SERVER;
        $this->status = \session_status();
    }

    /**
     * Set session handler for new instance.
     *
     * @param SessionHandlerInterface $handler
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
}
