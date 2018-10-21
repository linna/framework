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
use Linna\Shared\ClassOptionsTrait;
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
    use ClassOptionsTrait;

    /**
     * @var array Config options for class
     */
    protected $options = [
        'expire'         => 1800,
        'name'           => 'linna_session',
        'cookieDomain'   => '/',
        'cookiePath'     => '/',
        'cookieSecure'   => false,
        'cookieHttpOnly' => true,
    ];

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
    public $name = '';

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
        //set options
        $this->setOptions($options);

        $this->name = $options['name'] ?? $this->options['name'];

        $this->status = session_status();
    }

    /**
     * Regenerate session_id without double cookie problem.
     */
    public function regenerate(): void
    {
        //invalidate cookie
        setcookie(session_name(), '', time());
        //regenerate session id
        session_regenerate_id();
        //set new cookie
        $this->setCookie();
        //store new session data
        $this->setSessionData(time());
    }

    /**
     * Set cookie.
     */
    private function setCookie(): void
    {
        setcookie(
            session_name(),
            session_id(),
            time() + $this->options['expire'],
            $this->options['cookiePath'],
            $this->options['cookieDomain'],
            $this->options['cookieSecure'],
            $this->options['cookieHttpOnly']
        );
    }

    /**
     * Start session.
     */
    public function start(): void
    {
        if (session_status() !== 2) {
            //prepare session start
            $this->prepare();

            //start session
            session_start();

            //link session super global to $data property
            $this->data = &$_SESSION;
        }

        //set new cookie
        $this->setCookie();

        //refresh session
        $this->refresh();
    }

    /**
     * Set session options before start.
     */
    private function prepare(): void
    {
        //setting session name
        session_name($this->options['name']);

        //overwrite session name
        $this->name = $this->options['name'];

        //standard cookie param
        session_set_cookie_params(
                $this->options['expire'],
                $this->options['cookiePath'],
                $this->options['cookieDomain'],
                $this->options['cookieSecure'],
                $this->options['cookieHttpOnly']
        );
    }

    /**
     * Refresh session.
     */
    private function refresh(): void
    {
        $time = time();

        if (isset($this->data['time']) && $this->data['time'] <= ($time - $this->options['expire'])) {

            //delete session data
            $this->data = [];

            //regenerate session
            $this->regenerate();
        }

        $this->setSessionData($time);
    }

    /**
     * Set Session Data.
     *
     * @param int $time
     */
    private function setSessionData(int $time): void
    {
        $this->id = session_id();
        $this->data['time'] = $time;
        $this->data['expire'] = $this->options['expire'];
        $this->status = session_status();
    }

    /**
     * Set session handler for new instance.
     *
     * @param SessionHandlerInterface $handler
     */
    public function setSessionHandler(SessionHandlerInterface $handler): void
    {
        //setting a different save handler if passed
        if ($handler instanceof SessionHandlerInterface) {
            session_set_save_handler($handler, true);
        }
    }

    /**
     * Destroy session.
     */
    public function destroy(): void
    {
        //delete session data
        $this->data = [];
        $this->id = '';

        //call session destroy
        session_destroy();

        //update status
        $this->status = session_status();
    }

    /**
     * Write session data and end session.
     */
    public function commit(): void
    {
        session_write_close();

        //update status
        $this->status = session_status();
    }
}
