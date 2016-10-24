<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Session;

use Linna\classOptionsTrait;
use SessionHandlerInterface;

/**
 * Manage session lifetime and session data
 * In this class sigleton patter was correct because constructor is private
 * https://en.wikipedia.org/wiki/Singleton_pattern
 *
 * @property int $time Time of session
 */
class Session
{
    use classOptionsTrait;
    
    /**
     * Utilized with classOptionsTrait
     * @var array $options Config options for class
     */
    protected $options = array(
        'expire' => 1800,
        'name' => 'APP_SESSION',
        'cookieDomain' => '',
        'cookiePath' => '',
        'cookieSecure' => false,
        'cookieHttpOnly' => true
    );
    
    /**
     * @var array $data Session data reference property
     */
    private $data;
    
     /**
     * @var object $instance Store only one instance of session
     */
    private static $instance;
    
    /**
     * @var array $opt Cache variable for pass option to constructor
     */
    private static $opt;
    
    /**
     * Contructor
     *
     * @param array $options Options for configure session
     */
    private function __construct(array $options)
    {
        //set options
        $this->options = $this->overrideOptions($this->options, $options);
        
        //start session
        $this->start();
        
        $this->data = &$_SESSION;
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
        
        //store new time for expire
        $this->data['time'] = $time;
        $this->data['expire'] = $this->options['expire'];
    }
    
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
    private function start()
    {
        //setting session name
        session_name($this->options['name']);

        //standard cookie param
        session_set_cookie_params(
                $this->options['expire'],
                $this->options['cookiePath'],
                $this->options['cookieDomain'],
                $this->options['cookieSecure'],
                $this->options['cookieHttpOnly']
        );

        //start session
        session_start();

        //set new cookie
        $this->setCookie();
    }
    
    /**
     * Refresh session
     *
     * @return null
     */
    public function refresh()
    {
        $time = time();
        
        if (isset($this->data['time']) && $this->data['time']  < ($time - $this->options['expire'])) {
        
            //delete session data
            $this->data = [];
            
            //regenerate session
            $this->regenerate();
            
            return;
        }

        $this->data['time'] = $time;
        $this->data['expire'] = $this->options['expire'];
    }
    
    /**
     * Singleton
     * Get always the same instance
     *
     * @return \self $instance
     */
    public static function getInstance(): Session
    {
        $instance = &self::$instance;

        if ($instance === null) {

            //create new instance
            $instance = new self(self::$opt);
        }
        
        $instance->refresh();

        return $instance;
    }
    
    /**
     * Destroy session instance
     *
     */
    public static function destroyInstance()
    {
        $instance = &self::$instance;
        
        if ($instance !== null) {
            session_destroy();
            //create new instance
            $instance = null;
        }
    }
           
    /**
     * Singleton
     * Set options for session instance
     *
     * @param array $options Session options
     */
    public static function withOptions(array $options)
    {
        self::$opt = $options;
    }
    
     /**
     * Singleton
     * Set session handler for new instance
     *
     * @param SessionHandlerInterface $handler Session handler
     */
    public static function setSessionHandler(SessionHandlerInterface $handler)
    {
        //setting a different save handler if passed
        if ($handler instanceof SessionHandlerInterface) {
            session_set_save_handler($handler, true);
        }
    }
}
