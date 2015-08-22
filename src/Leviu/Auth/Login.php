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

namespace Leviu\Auth; 

//use App_mk0\DatabasePasswordHandler;

/**
 * Login
 * - Class a for autenticate users :)
 *
 * Utilize for do login
 * 
 *      <?php
 *      $username = 'foo';
 *      $password = 'an hashed passowrd';
 * 
 *      $login = new \App_mk0\Login();
 *      $login->login($username, $password);
 *      
 *      //do actions
 * 
 *      $login->logout;
 * 
 * Utilize for check login
 * 
 *      <?php
 *      $login = new \App_mk0\Login();
 * 
 *      if ($login->isLogged === true)
 *      {
 *              //do actions
 *      }
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Login
{
    /**
     * @var int Current user id
     */
    public $userId = 0;
    
    /**
     * @var string Current user name
     */
    public $userName = '';
   
    /**
     * $login->isLogged can be check if user is logged
     * @var bool User login status
     */
    public $isLogged = false;
    
    
    
    /**
     * @var int Numeber of seconds before login will considered invalid
     */
    private $loginExpire = 1800;
    
    
    /**
     * Login constructor
     * 
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->isLogged = $this->check();
    }
    
    /**
     * Login
     * 
     * Try to log user passed by param, return true if ok else false
     * 
     * @param string $userName
     * @param string $password
     * @return boolean
     * @since 0.1.0
     */
    public function login($userName, $password)// passare un oggetto utente ed un oggetto password
    {
        $userMapper = new UserMapper();

        $user = $userMapper->findByName($userName);
        
        if ($user !== false) {
            if (password_verify($password, $user->password) && $user->active === 1) {
                $id = $user->getId();

                $this->userId = $id;
                $this->userName = $user->name;
                
                $this->isLogged = true;
                        
                $_SESSION['login']=
                    [
                        'user_id' => $id,
                        'user_name' => $user->name,
                        'time' => time()
                    ];
                
                session_regenerate_id(true);
                session_write_close();
                
                return true;
            }
        }

        return false;
    }

    /**
     * Logout
     * 
     * For do logout, delete login information from session
     * 
     * @return boolean
     * @since 0.1.0
     */
    public function logout()
    {
        unset($_SESSION['login']);
        
        session_regenerate_id(true);
        session_write_close();
        
        return true;
    }

    /**
     * Check
     * 
     * For check if user is logged, get login data from session and update it
     * 
     * @return boolean
     * @since 0.1.0
     */
    private function check()
    {
        if (isset($_SESSION['login'])) {
            $loginData = $_SESSION['login'];
            
            $time = time();
            
            if ($loginData['time'] > ($time - $this->loginExpire)) {
                $loginData['time'] = $time;
                
                $this->userId = $loginData['user_id'];
                $this->userName = $loginData['user_name'];

                $_SESSION['login'] = $loginData;
                
                return true;
            }
        }
        
        return false;
    }
}
