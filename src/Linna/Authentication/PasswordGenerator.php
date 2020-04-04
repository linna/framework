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

use InvalidArgumentException;

/**
 * Generate random password with random topology, this class use php7
 * random_int() function for generate random numbers.
 */
class PasswordGenerator
{
    /**
     * @var array<string> Characters intervall
     */
    private array $chars = [
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ', //u 117
        'abcdefghijklmnopqrstuvwxyz', //l 108
        '0123456789', //d 100
        '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~' //s 115
    ];

    /**
     * Class constructor.
     *
     * In this class constructor do nothing, is present only for compatibility with Container.
     *
     * <pre><code class="php">use Linna\Auth\PasswordGenerator;
     *
     * $passwordGenerator = new PasswordGenerator();
     * </code></pre>
     */
    public function __construct()
    {
    }

    /**
     * Generate a random password.
     *
     * <pre><code class="php">$random = $passwordGenerator->getFromRandom(20);
     *
     * //var_dump result
     * //r4Q,1J*tM7D_99q0u>61
     * var_dump($random);
     * </code></pre>
     *
     * @param int $length Desiderated password length.
     *
     * @return string Random password.
     */
    public function getFromRandom(int $length): string
    {
        $password = [];

        while ($length) {
            $password[] = $this->getRandomChar($this->chars[\random_int(0, 3)]);

            $length--;
        }

        return \implode($password);
    }

    /**
     * Return topology for given password.
     *
     * <pre><code class="php">$topology = $passwordGenerator->getTopology('r4Q,1J*tM7D_99q0u>61');
     *
     * //var_dump result
     * //ldusdusludusddldlsdd
     * var_dump($topology);
     * </code></pre>
     *
     * @param string $password Password for which get topology.
     *
     * @return string Topology for the argument passed password.
     */
    public function getTopology(string $password): string
    {
        $array = \str_split($password);
        $topology = [];

        foreach ($array as $char) {
            $topology[] = $this->getTopologyGroup($char);
        }

        return \implode($topology);
    }

    /**
     * Return topology group for the given char.
     *
     * @param string $char
     *
     * @return string
     *
     * @throws InvalidArgumentException If char provided isn't inside any group
     */
    private function getTopologyGroup(string $char): string
    {
        $groups = $this->chars;

        if (\strpos($groups[0], $char) !== false) {
            return 'u';
        }

        if (\strpos($groups[1], $char) !== false) {
            return 'l';
        }

        if (\strpos($groups[2], $char) !== false) {
            return 'd';
        }

        if (\strpos($groups[3], $char) !== false) {
            return 's';
        }

        throw new InvalidArgumentException('Out of group character provided.');
    }

    /**
     * Generate a random password corresponding at the given topology.
     *
     * <pre><code class="php">$random = $passwordGenerator->getFromTopology('ldusdusludusddldlsdd');
     *
     * //var_dump result
     * //r4Q,1J*tM7D_99q0u>61
     * var_dump($random);
     * </code></pre>
     *
     * @param string $topology Topology for generate password.
     *
     * @return string Random password corresponding the given topology.
     *
     * @throws InvalidArgumentException If invalid pattern is provided.
     */
    public function getFromTopology(string $topology): string
    {
        $array = \str_split(\strtolower($topology));
        $groups = [117 => 0, 108 => 1, 100 => 2, 115 => 3];
        $password = [];

        foreach ($array as $char) {
            $int = \ord($char);

            if (isset($groups[$int])) {
                $password[] = $this->getRandomChar($this->chars[$groups[$int]]);

                continue;
            }

            throw new InvalidArgumentException('Invalid pattern provided, accepted only u, l, d and s.');
        }

        return \implode($password);
    }

    /**
     * Get random char between.
     *
     * @param string $interval
     *
     * @return string
     */
    private function getRandomChar(string $interval): string
    {
        $size = \strlen($interval) - 1;
        $int = \random_int(0, $size);

        return $interval[$int];
    }
}
