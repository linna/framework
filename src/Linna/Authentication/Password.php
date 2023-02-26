<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

/**
 * Provide methods to manage password, this class uses Libsodium password hashing function, see php documentation 
 * for more information.
 *
 * @link https://www.php.net/manual/en/function.sodium-crypto-pwhash-str.php
 */
final class Password
{
    /**
     * Class constructor.
     *
     * <p>
     * Computations values
     *  SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE = 2
     *  SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE    = 3
     *  SODIUM_CRYPTO_PWHASH_OPSLIMIT_SENSITIVE   = 4
     *
     * Memory values
     *  SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE = 67108864
     *  SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE    = 268435456
     *  SODIUM_CRYPTO_PWHASH_MEMLIMIT_SENSITIVE   = 1073741824
     * </p>
     *
     * @param int $opsLimit Represents a maximum amount of computations to perform. Raising this number will make the
     *                      function require more CPU cycles to compute a key. There are constants available to set the
     *                      operations limit to appropriate values depending on intended use, in order of strength:
     *                      SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE and
     *                      SODIUM_CRYPTO_PWHASH_OPSLIMIT_SENSITIVE.
     * @param int $memLimit The maximum amount of RAM that the function will use, in bytes. There are constants to help
     *                      you choose an appropriate value, in order of size:
     *                      SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE, and
     *                      SODIUM_CRYPTO_PWHASH_MEMLIMIT_SENSITIVE. Typically these should be paired with the matching
     *                      opslimit values.
     *
     * @link https://www.php.net/manual/en/function.sodium-crypto-pwhash-str.php
     */
    public function __construct(
        private int $opsLimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
        private int $memLimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
    )
    {
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
        return \sodium_crypto_pwhash_str_verify($hash, $password);
    }

    /**
     * Create password hash from the given string and return it.
     *
     * @param string $password The password to generate a hash for.
     *
     * @return string The hashed password.
     */
    public function hash(string $password): string
    {
        return \sodium_crypto_pwhash_str($password, $this->opsLimit, $this->memLimit);
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
        return \sodium_crypto_pwhash_str_needs_rehash($hash, $this->opsLimit, $this->memLimit);
    }

    /**
     * Return information about the given hash.
     *
     * @param string $hash Hash for which get info.
     *
     * @return array<mixed> Returns an associative array with three elements:
     *                      1, algo, which will match a password algorithm constant.
     *                      2, algoName, which has the human readable name of the algorithm.
     *                      3, options, which includes the options provided.
     */
    public function getInfo(string $hash): array
    {
        return \password_get_info($hash);
    }
}
