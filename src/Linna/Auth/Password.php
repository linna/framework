<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

/**
 * Class for manage password, using PHP 5.5.0 password, see php documentation for more information.
 *
 * http://php.net/manual/en/ref.password.php.
 */
class Password
{
    /**
     * @var array An associative array containing options
     *
     * http://php.net/manual/en/password.constants.php
     */
    protected $options = [
            'cost' => 11,
            'algo' => PASSWORD_DEFAULT,
        ];

    /**
     * Constructor.
     *
     * @param array $options Options for password hashing
     */
    public function __construct(array $options = [])
    {
        //set options
        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * Verifies that a password matches a hash.
     *
     * @param string $password Password to be verified
     * @param string $hash     Hash for password
     *
     * @return bool Result of password_verify PHP function.
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Create a password hash.
     *
     * @param string $password Password to be hashed
     *
     * @return string Return the hashed password
     */
    public function hash(string $password): string
    {
        //generate hash from password
        return password_hash($password, $this->options['algo'], $this->options);
    }

    /**
     * Checks if the given hash matches the given options.
     *
     * @param string $hash Hash for check
     *
     * @return bool Return the hashed password
     */
    public function needsRehash(string $hash): bool
    {
        if (password_needs_rehash($hash, $this->options['algo'], $this->options)) {
            return true;
        }

        return false;
    }

    /**
     * Returns information about the given hash.
     *
     * @param string $hash Hash to get info
     *
     * @return array
     */
    public function getInfo(string $hash) : array
    {
        return password_get_info($hash);
    }
}
