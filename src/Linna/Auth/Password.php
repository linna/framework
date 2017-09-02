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

use Linna\Shared\ClassOptionsTrait;

/**
 * Password.
 *
 * Class for manage password, using PHP password hashing function,
 * see php documentation for more information.
 * <br/>
 * <a href="http://php.net/manual/en/book.password.php">http://php.net/manual/en/book.password.php</a>
 */
class Password
{
    use ClassOptionsTrait;

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
     * Class constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        //set options
        $this->setOptions($options);
    }

    /**
     * Verify.
     *
     * Verifies if a password matches a hash and return the result as boolean.
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash.
     *
     * Create password hash from the given string and return it.
     *
     * @param string $password
     *
     * @return string
     */
    public function hash(string $password): string
    {
        //generate hash from password
        return password_hash($password, $this->options['algo'], $this->options);
    }

    /**
     * needsRehash.
     *
     * Checks if the given hash matches the algorithm and the options provided. 
     *
     * @param string $hash
     *
     * @return bool
     */
    public function needsRehash(string $hash): bool
    {
        if (password_needs_rehash($hash, $this->options['algo'], $this->options)) {
            return true;
        }

        return false;
    }

    /**
     * getInfo.
     *
     * Returns information about the given hash.
     *
     * @param string $hash
     *
     * @return array
     */
    public function getInfo(string $hash) : array
    {
        return password_get_info($hash);
    }
}
