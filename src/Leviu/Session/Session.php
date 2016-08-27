<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Session;

use SessionHandlerInterface;

/**
 * Session
 * - Class for manage session lifetime
 * In this class sigleton patter was correct because constructor is private
 * https://it.wikipedia.org/wiki/Singleton.
 */
class Session
{

    /**
     * @var int $expire Expiration time for session
     */
    private static $expire = 1800;

    /**
     * @var string $name Session name
     */
    private static $name = 'PHPSESSID';

    /**
     * @var object $handler Instance of SessionHandlerInterface
     */
    private static $handler = null;

    /**
     * http://php.net/manual/en/function.setcookie.php.
     * 
     * @var string $cookieDomain
     */
    private static $cookieDomain = null;

    /**
     * http://php.net/manual/en/function.setcookie.php.
     * 
     * @var string $cookiePath
     */
    private static $cookiePath = null;

    /**
     * @var object $instance
     */
    private static $instance;
    
    /**
     *
     * @var type $data Session stored data
     */
    private static $data = array();

    /**
     * Session constructor
     * 
     */
    private function __construct(&$sessionData)
    {   
        if (!isset($sessionData['time'])) {
            $sessionData['time'] = time();
        }
        
        self::$data = &$sessionData;
        
    }

    /**
     * Check if session is expired
     * 
     */
    private function isExpired()
    {
        $time = time();
        
        if (self::$data['time'] < ($time - self::$expire)) {
            
            //delete session data
            self::$data = [];
            
            //regenerate session
            $this->regenerate();
            
            return;
        }

        self::$data['time'] = $time;
    }

    /**
     * Forbids the object clone
     * 
     */
    private function __clone()
    {
        //It forbids the object clone
    }

    /**
     * Return singleton instance
     * 
     * @return object
     */
    public static function getInstance()
    {
        $instance = &self::$instance;

        if ($instance === null) {

            //create new instance
            $instance = self::createInstance();
        }

        $instance->isExpired();

        return $instance;
    }

    /**
     * Create session instance
     * 
     * @return \self
     */
    private function createInstance()
    {
        //setting session name
        session_name(self::$name);

        //standard cookie param
        session_set_cookie_params(self::$expire, self::$cookiePath, self::$cookieDomain, 0, 1);

        //start session
        session_start();

        //set cookies
        setcookie(session_name(), session_id(), time() + self::$expire, 0, 1);

        //create new Session :)
        return new self($_SESSION);
    }
    
    /**
     * Set session options
     * 
     * @param object $options
     */
    public static function setOptions($options)
    {
        self::$expire = $options->expire;
        self::$name = $options->name;
        self::$cookieDomain = $options->cookieDomain;
        self::$cookiePath = $options->cookiePath;
    }
    
    /**
     * Set session handler for different storage
     * 
     * @param SessionHandlerInterface $handler
     */
    public static function setSessionHandler(SessionHandlerInterface $handler)
    {
        //setting a different save handler if passed
        if ($handler instanceof SessionHandlerInterface) {

            self::$handler = $handler;
            session_set_save_handler($handler, true);
        }
    }

    /**
     * Regenerate session_id without double cookie problem
     * 
     * @return object
     *
     */
    public function regenerate()
    {
        $time = time();

        setcookie(session_name(), '', $time - 86400);

        session_regenerate_id(true);

        setcookie(session_name(), session_id(), $time + self::$expire, 0, 1);
        
        self::$data['time'] = $time;
    }

}
