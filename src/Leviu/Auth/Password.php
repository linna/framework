<?php

/**
 * Leviu.
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
namespace Leviu\Auth;

/**
 * Class for manage password, using PHP 5.5.0 password see php documentation for more information
 * http://php.net/manual/en/ref.password.php.
 * 
 */
class Password
{
    /**
     *
     * @var array $options An associative array containing options
     * 
     * http://php.net/manual/en/password.constants.php
     */
    protected $options = [
            'cost' => 11
        ];
    
    /**
     * Constructor
     * 
     */
    public function __construct()
    {
    }
    
    /**
     * Check if password matches a hash.
     * 
     * @param string $hash     Password hashed.
     * @param string $password Password to be verified.
     * 
     * @return bool Result of password_verify PHP function.
     *
     */
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Create a password hash.
     * 
     * @param string $password Password to be hashed.
     * 
     * @return string Return the hashed password.
     *
     * @since 0.1.0
     */
    public function hash($password)
    {
        //generate hash from password
        $hash = password_hash($password, PASSWORD_DEFAULT, $this->options);
        
        return $hash;
    }
    
    /**
     * Check if password need rehash
     * 
     * @param string $password Password for check.
     * @param string $hash Hash for check.
     * 
     * @return string Return the hashed password.
     *
     * @since 0.1.4
     */
    public function needs_rehash($hash)
    {
        if (password_needs_rehash($hash, PASSWORD_DEFAULT, $this->options)) {
            return true;
        }
        
        return false;
    }
}
