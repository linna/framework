<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

/**
 * Provide methods for manage password, this class use PHP password hashing function,
 * see php documentation for more information.
 * <a href="http://php.net/manual/en/book.password.php">http://php.net/manual/en/book.password.php</a>
 */
class Password
{
    /**
     * @var array<mixed> An associative array containing options
     *
     * http://php.net/manual/en/function.password-hash.php
     */
    protected array $options = [
        PASSWORD_BCRYPT => ['cost' => 11],
        PASSWORD_DEFAULT => ['cost' => 11]
    ];

    /**
     * @var array<mixed> An associate array containing algorithm constants
     */
    protected array $algoLists = [
        PASSWORD_BCRYPT,
        PASSWORD_DEFAULT
    ];

    /**
     * @var string|null Password default algorithm
     */
    protected ?string $algo = PASSWORD_BCRYPT;

    /**
     * Class constructor.
     *
     * <p>For password algorithm constants see <a href="http://php.net/manual/en/password.constants.php">Password Constants</a>.</p>
     * <pre><code class="php">use Linna\Authentication\Password;
     *
     * $password = new Password(PASSWORD_DEFAULT, [
     *     'cost' => 11
     * ]);
     * </code></pre>
     *
     * Strict typing removed for $algo because on php 7.4 password hashing
     * algorithm identifiers are nullable strings rather than integers.
     *
     * @param string|null   $algo        Algorithm used for hash passwords.
     * @param array<mixed>  $options     Options for algoas ['key' => 'value'] array.
     *
     * @throws \InvalidArgumentException
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
        $this->options[$algo] = \array_replace_recursive($this->options[$algo], $options);
    }

    /**
     * Verifies if a password matches an hash and return the result as boolean.
     *
     * <pre><code class="php">$storedHash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';
     * $password = 'FooPassword';
     *
     * $verified = $password->verify($password, $storedHash);
     * </code></pre>
     *
     * @param string $password  Plaintext password to be compared.
     * @param string $hash      Hashed password.
     *
     * @return bool True if password match, false if not.
     */
    public function verify(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }

    /**
     * Create password hash from the given string and return it.
     *
     * <pre><code class="php">$hash = $password->hash('FooPassword');
     *
     * //var_dump result
     * //$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6
     * var_dump($hash)
     * </code></pre>
     *
     * @param string $password Plaintext password to be hashed.
     *
     * @return string Hashed password.
     */
    public function hash(string $password): string
    {
        return \password_hash($password, $this->algo, $this->options[$this->algo]);
    }

    /**
     * Checks if the given hash matches the algorithm and the options provided.
     *
     * <pre><code class="php">$hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';
     *
     * //true if rehash is needed, false if no
     * $rehashCheck = $password->needsRehash($hash);
     * </code></pre>
     *
     * @param string $hash Hash to be checked
     *
     * @return bool
     */
    public function needsRehash(string $hash): bool
    {
        return \password_needs_rehash($hash, $this->algo, $this->options[$this->algo]);
    }

    /**
     * Returns information about the given hash.
     *
     * <pre><code class="php">$hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';
     *
     * $info = $password->getInfo($hash);
     *
     * //var_dump result
     * //[
     * //    'algo' => 1,
     * //    'algoName' => 'bcrypt',
     * //    'options' => [
     * //        'cost' => int 11
     * //    ]
     * //]
     * var_dump($info);
     * </code></pre>
     *
     * @param string $hash Hash for wich get info.
     *
     * @return array<mixed>
     */
    public function getInfo(string $hash): array
    {
        return \password_get_info($hash);
    }
}
