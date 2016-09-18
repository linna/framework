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

namespace Linna\Auth;

/**
 * Class for manage password, using PHP 5.5.0 password see php documentation for more information
 *
 * http://php.net/manual/en/ref.password.php.
 */
class Password
{
    /**
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
     * @param string $hash
     * @param string $password
     *
     * @return bool Result of password_verify PHP function.
     */
    public function verify(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Create a password hash.
     *
     * @param string $password Password to be hashed.
     *
     * @return string Return the hashed password.
     */
    public function hash(string $password)
    {
        //generate hash from password
        $hash = password_hash($password, PASSWORD_DEFAULT, $this->options);
        
        return $hash;
    }
    
    /**
     * Check if password need rehash
     *
     * @param string $hash Hash for check.
     *
     * @return boolean Return the hashed password.
     */
    public function needsRehash(string $hash)
    {
        if (password_needs_rehash($hash, PASSWORD_DEFAULT, $this->options)) {
            return true;
        }
        
        return false;
    }
}
