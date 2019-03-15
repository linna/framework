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
     * @var array An associative array containing options
     *
     * http://php.net/manual/en/function.password-hash.php
     */
    protected $options = [
        1 => ['cost' => 11],
        2 => [
            'memory_cost' => 1024,
            'time_cost' => 2,
            'threads' => 2
        ]
    ];

    /**
     * @var int Password default algorithm
     */
    protected $algo = 1;

    /**
     * Class constructor.
     * <p>For password algorithm constants see <a href="http://php.net/manual/en/password.constants.php">Password Constants</a>.</p>
     * <pre><code class="php">//Options passed to class constructor as ['key' => 'value'] array.
     * $password = new Password(PASSWORD_DEFAULT, [
     *     'cost' => 11
     * ]);
     * </code></pre>
     *
     * @param int   $algo
     * @param array $options
     */
    public function __construct(int $algo = PASSWORD_DEFAULT, array $options = [])
    {
        $this->algo = $algo;

        $this->options[$algo] = \array_replace_recursive($this->options[$algo], $options);
    }

    /**
     * Verifies if a password matches an hash and return the result as boolean.
     * <pre><code class="php">$password = new Password();
     *
     * $storedHash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';
     * $password = 'FooPassword';
     *
     * $verified = $password->verify($password, $storedHash);
     * </code></pre>
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool True if password match, false if not.
     */
    public function verify(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }

    /**
     * Create password hash from the given string and return it.
     * <pre><code class="php">$password = new Password();
     *
     * $hash = $password->hash('FooPassword');
     *
     * //var_dump result
     * //$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6
     * var_dump($hash)
     * </code></pre>
     *
     * @param string $password
     *
     * @return string Hashed password.
     */
    public function hash(string $password): string
    {
        $hash = \password_hash($password, $this->algo, $this->options[$this->algo]);

        return $hash;
    }

    /**
     * Checks if the given hash matches the algorithm and the options provided.
     * <pre><code class="php">$password = new Password();
     *
     * $hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';
     *
     * //true if rehash is needed, false if no
     * $rehashCheck = $password->needsRehash($hash);
     * </code></pre>
     *
     * @param string $hash
     *
     * @return bool
     */
    public function needsRehash(string $hash): bool
    {
        return \password_needs_rehash($hash, $this->algo, $this->options[$this->algo]);
    }

    /**
     * Returns information about the given hash.
     * <pre><code class="php">$password = new Password();
     *
     * $hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';
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
     * @param string $hash
     *
     * @return array
     */
    public function getInfo(string $hash): array
    {
        return \password_get_info($hash);
    }
}
