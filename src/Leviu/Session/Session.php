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

use SessionHandler;
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
        //$sessionTime = $_SESSION['time'];

        if ($_SESSION['time'] < ($time - self::$expire)) {
            //setcookie(session_name(), '', time() - 86400);
            
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
        if (self::$handler !== null && self::$handler !== '' && self::$handler instanceof SessionHandlerInterface) {
            session_set_save_handler(self::$handler, true);
        }
        
        if (self::$instance === null) {
            session_name(self::$name);
            
            session_set_cookie_params(self::$expire, URL_SUB_FOLDER, URL_DOMAIN, 0, 1);
            
            session_start();
            
            setcookie(session_name(), session_id(), time() + self::$expire, 0, 1);
            
            self::$instance = new Session();
        }
        
        self::$instance->isExpired();
                
        return self::$instance;
    }
    
    /**
     * regenerate
     * 
     * regenerate session_id without double cookie problem
     * 
     * @return object
     * @since 0.1.0
     */
    public function regenerate()
    {
        setcookie(session_name(), '', time() - 86400);
        
        session_regenerate_id(true);
        
        setcookie(session_name(), session_id(), time() + self::$expire, 0, 1);
    }
}
