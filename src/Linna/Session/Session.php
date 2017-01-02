<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Session;

use \SessionHandlerInterface;

/**
 * Manage session lifetime and session data
 *
 * @property int $time Time of session
 * @property array $login Login information set by Login class
 * @property int $loginTime Login time set by Login class
 * @property int $expire Login time set for Login class
 */
class Session
{
    /**
     * @var array $options Config options for class
     */
    protected $options = array(
        'expire' => 1800,
        'name' => 'LINNA_SESSION',
        'cookieDomain' => '/',
        'cookiePath' => '/',
        'cookieSecure' => false,
        'cookieHttpOnly' => true
    );
    
    /**
     * @var array $data Session data reference property
     */
    private $data;
    
    /**
     * @var string $id Session id
     */
    public $id;
    
    /**
     * @var string $name Session name
     */
    public $name;
    
    /**
     * Constructor
     *
     * @param array $options Options for configure session
     */
    public function __construct(array $options = array())
    {
        //set options
        $this->options = array_replace_recursive($this->options, $options);
        
        $this->name = $options['name'] ?? $this->options['name'];
    }
    
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        
        return false;
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __unset(string $name)
    {
        unset($this->data[$name]);
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __isset(string $name)
    {
        return isset($this->data[$name]);
    }
    
    /**
     * Regenerate session_id without double cookie problem
     *
     */
    public function regenerate()
    {
        //take time
        $time = time();
        //invalidate cookie
        setcookie(session_name(), '', $time - 86400);
        //regenerate session id
        session_regenerate_id(true);
        
        //set new cookie
        $this->setCookie();
        
        //store id and new time for expire
        $this->id = session_id();
        $this->data['time'] = $time;
        $this->data['expire'] = $this->options['expire'];
    }
    
    /**
     * Set cookie
     *
     */
    private function setCookie()
    {
        setcookie(
                session_name(),
                session_id(),
                time() + $this->options['expire'],
                (string) $this->options['cookieSecure'],
                (string) $this->options['cookieHttpOnly']
        );
    }
    
    /**
     * Start session
     *
     */
    public function start()
    {
        if (session_status() !== 2) {
            //prepare session start
            $this->prepare();

            //start session
            session_start();
        
            //set new cookie
            $this->setCookie();

            //link session super global to $data property
            $this->data = &$_SESSION;
        }
        
        //refresh session
        $this->refresh();
    }
    
    /**
     * Set session options before start
     *
     */
    private function prepare()
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
     * Refresh session
     *
     * @return null
     */
    private function refresh()
    {
        $time = time();
        
        if (isset($this->data['time']) && $this->data['time']  < ($time - $this->options['expire'])) {
        
            //delete session data
            $this->data = [];
            
            //regenerate session
            $this->regenerate();
            
            return;
        }
        
        $this->id = session_id();
        $this->data['time'] = $time;
        $this->data['expire'] = $this->options['expire'];
    }
    
    /**
     * Set session handler for new instance
     *
     * @param SessionHandlerInterface $handler Session handler
     */
    public function setSessionHandler(SessionHandlerInterface $handler)
    {
        //setting a different save handler if passed
        if ($handler instanceof SessionHandlerInterface) {
            session_set_save_handler($handler, true);
        }
    }
    
    /**
     * Destroy session
     *
     */
    public function destroy()
    {
        //delete session data
        $this->data = [];
        
        //call session destroy
        session_destroy();
    }
    
    /**
     * Write session data and end session
     *
     */
    public function commit()
    {
        session_write_close();
    }
}
