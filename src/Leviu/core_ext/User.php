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

namespace Leviu\core_ext;

/**
 * User
 * - Class for manage users
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class User extends DomainObjectAbstract
{
    /**
     * @var strng User name
     */
    public $name;
    
    /**
     * @var string User description
     */
    public $description;
    
    /**
     * @var string User password
     */
    public $password;
    
    /**
     * @var int It say if user is active or not
     */
    public $active = 0;
    
    /**
     * @var string User creation date
     */
    public $created;
    
    /**
     *
     * @var string Last user update
     */
    public $last_update;
    
    
    /**
     * User Constructor
     * 
     * Do type conversion because PDO doesn't return any original type from db :(
     * 
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->active = (int) $this->active;
        $this->_id = (int) $this->_id;
    }
    
    /**
     * setPassword
     * 
     * Set new user password without do any check
     * 
     * @param string $newPassword
     * @since 0.1.0
     */
    public function setPassword($newPassword)
    {
        $passUtil = new Password();
        
        $hash = $passUtil->hash($newPassword);
        
        $this->password = $hash;
    }
    
    /**
     * setPassword
     * 
     * Set new user password only after check old password
     * 
     * @param string $newPassword New user password
     * @param string $oldPassword Old user password
     * @return boolean
     * @since 0.1.0
     */
    public function chagePassword($newPassword, $oldPassword)
    {
        $passUtil = new Password();
        
        $hash = $passUtil->hash($newPassword);
        
        if (password_verify($oldPassword, $this->password)) {
            $this->password = $hash;
            
            return true;
        }

        return false;
    }
}
