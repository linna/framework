<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

/**
 * Provide methods to manage password, this class uses PHP password hashing
 * function, see php documentation for more information.
 *
 * @link http://php.net/manual/en/book.password.php
 */
class Password
{
    /**
     * @var array<mixed> An associative array containing options.
     *
     * @link http://php.net/manual/en/function.password-hash.php
     */
    protected array $options = [
        // commented because value is the same as PASSWORD_DEFAULT
        //PASSWORD_BCRYPT => ['cost' => 11],
        PASSWORD_DEFAULT => ['cost' => 11]
    ];

    /** @var array<mixed> An associate array containing algorithm constants. */
    protected array $algoLists = [
        // commented because value is the same as PASSWORD_DEFAULT
        //PASSWORD_BCRYPT,
        PASSWORD_DEFAULT
    ];

    /** @var string|null Password default algorithm. */
    protected ?string $algo = PASSWORD_DEFAULT;

    /**
     * Class constructor.
     *
     * <p>For password algorithm constants see
     * <a href="http://php.net/manual/en/password.constants.php">password constants</a>.</p>
     *
     * <p>Strict typing removed for <code>$algo</code> because on PHP 7.4 password hashing
     * algorithm identifiers are nullable strings rather than integers.</p>
     *
     * @param string|null  $algo    Algorithm used for hash passwords.
     * @param array<mixed> $options Options for algos <code>['key' => 'value']</code> array.
     *
     * @throws \InvalidArgumentException If the <code>$algo</code> paramether contains an
     *                                   invalid password algorithm.
     *
     * @todo Check for PASSWORD_ARGON2I PASSWORD_ARGON2ID default definition.
     */
    public function __construct($algo = PASSWORD_BCRYPT, array $options = [])
    {
        //necessary for avoid errors if Argon2 library not enabled
        //PASSWORD_ARGON2ID const only present since 7.3 PHP version
        if (\defined('PASSWORD_ARGON2I')) {
            $this->algoLists[] = PASSWORD_ARGON2I;
            $this->options[PASSWORD_ARGON2I] = [
                'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
            ];
        }

        if (\defined('PASSWORD_ARGON2ID')) {
            $this->algoLists[] = PASSWORD_ARGON2ID;
            $this->options[PASSWORD_ARGON2ID] = [
                'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
            ];
        }

        if (!\in_array($algo, $this->algoLists, true)) {
            throw new \InvalidArgumentException("The password algorithm {$algo} is invalid");
        }

        $this->algo = $algo;
        $this->options[$algo] = array_replace_recursive($this->options[$algo], $options);
    }

    /**
     * Verify if a password matches an hash and return the result as boolean.
     *
     * @param string $password Plaintext password to be compared.
     * @param string $hash     Hashed password.
     *
     * @return bool True if password match, false otherwise.
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Create password hash from the given string and return it.
     *
     * @param string $password Plaintext password to be hashed.
     *
     * @return string Hashed password.
     */
    public function hash(string $password): string
    {
        return password_hash($password, $this->algo, $this->options[$this->algo]);
    }

    /**
     * Check if the given hash matches the algorithm and the options provided.
     *
     * @param string $hash Hash to be checked.
     *
     * @return bool True if the password need re-hash, false otherwise.
     */
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, $this->algo, $this->options[$this->algo]);
    }

    /**
     * Return information about the given hash.
     *
     * @param string $hash Hash for which get info.
     *
     * @return array<mixed> Information for the hash.
     */
    public function getInfo(string $hash): array
    {
        return password_get_info($hash);
    }
}
