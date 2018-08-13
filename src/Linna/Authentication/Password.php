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

use Linna\Shared\ClassOptionsTrait;
use UnexpectedValueException;

/**
 * Provide methods for manage password, this class use PHP password hashing function,
 * see php documentation for more information.
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
     * Class constructor.
     * <p><b>$options valid keys:</b></p>
     * <table class="parameter">
     * <thead>
     * <tr>
     * <th>Name</th>
     * <th>Default</th>
     * <th>Description</th>
     * </tr>
     * </thead>
     * <tbody>
     * <tr>
     * <td>cost</td>
     * <td>11</td>
     * <td>indicating key expansion rounds</td>
     * </tr>
     * <tr>
     * <td>algo</td>
     * <td>PASSWORD_DEFAULT</td>
     * <td>password algorithm denoting the algorithm to use when hashing the password</td>
     * </tr>
     * </tbody>
     * </table>
     * <p>For password algorithm constants see <a href="http://php.net/manual/en/password.constants.php">Password Constants</a>.</p>
     * <pre><code class="php">//Options passed to class constructor as ['key' => 'value'] array.
     * $password = new Password([
     *     'cost' => 11,
     *     'algo' => PASSWORD_DEFAULT,
     * ]);
     * </code></pre>
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
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
        return password_verify($password, $hash);
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
     * @return string
     */
    public function hash(string $password): string
    {
        $hash = password_hash($password, $this->options['algo'], $this->options);

        if ($hash === false) {
            throw new UnexpectedValueException('Password hashing fails.');
        }

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
        return password_needs_rehash($hash, $this->options['algo'], $this->options);
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
        return password_get_info($hash);
    }
}
