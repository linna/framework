<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\Session;

//use SessionHandler;
use SessionHandlerInterface;

/**
 * Session
 * - Class for manage session lifetime
 * In this class sigleton patter was correct because constructor is private
 * https://it.wikipedia.org/wiki/Singleton
 */
class Session
{
    /**
     * @var int $expire Expiration time for session
     */
    public static $expire = 1800;
    
    /**
     * @var string $name  Session name
     */
    public static $name = 'PHPSESSID';
    
    /**
     * @var object $handler  Instance of SessionHandlerInterface
     */
    public static $handler = null;
    
    /**
     * http://php.net/manual/en/function.setcookie.php
     * 
     * @var string $cookieDomain
     */
    public static $cookieDomain = null;
    
    /**
     * http://php.net/manual/en/function.setcookie.php
     * 
     * @var string $cookiePath
     */
    public static $cookiePath = null;
    
    /**
     * @var object $instance
     * @static object $instance The Session instance
     */
    private static $instance;
    
    /**
     * Session constructor
     * 
     * @since 0.1.0
     */
    private function __construct()
    {
        if (!isset($_SESSION['time'])) {
            $_SESSION['time'] = time();
        }
    }
    
    /**
     * isExpired
     * 
     * check if session is expired
     * 
     * @since 0.1.0
     */
    private function isExpired()
    {
        $time = time();
        
        if ($_SESSION['time'] < ($time - self::$expire)) {

            
            $this->regenerate();
            
            return null;
        }
        
        $_SESSION['time'] = $time;
    }
    
    
    
    /**
     * __clone
     * 
     * Forbids the object clone
     * 
     * @since 0.1.0
     */
    private function __clone()
    {
        //It forbids the object clone
    }

    /**
     * start
     * 
     * return te instance of session
     * 
     * @return object
     * @since 0.1.0
     */
    public static function getInstance()
    {
        $h = &self::$handler;
        $i = &self::$instance;
        
        //setting a different save handler if passed
        if ($h !== null && $h !== '' && $h instanceof SessionHandlerInterface) {
            session_set_save_handler($h, true);
        }
        
        if ($i === null) {
            
            //setting session name
            session_name(self::$name);
            
            //standard cookie param
            session_set_cookie_params(self::$expire, self::$cookiePath, self::$cookieDomain, 0, 1);
            
            //start session
            session_start();
            
            //set cookies
            setcookie(session_name(), session_id(), time() + self::$expire, 0, 1);
            
            //create new Session :)
            $i = new Session();
        }
        
        $i->isExpired();
                
        return $i;
    }
    
    /**
     * regenerate
     * 
     * regenerate session_id without double cookie problem
     * 
     * @return object
     * @since 0.1.1
     */
    public function regenerate()
    {
        $time = time();
        
        setcookie(session_name(), '', $time - 86400);
        
        session_regenerate_id(true);
        
        setcookie(session_name(), session_id(), $time + self::$expire, 0, 1);
    }
}
