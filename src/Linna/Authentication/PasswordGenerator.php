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

use InvalidArgumentException;

/**
 * Generate random password with random topology, this class use PHP 7 random_int() function for generate random
 * numbers.
 */
class PasswordGenerator
{
    /** @var array<string> Characters intervall */
    private array $chars = [
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ', //u 117
        'abcdefghijklmnopqrstuvwxyz', //l 108
        '0123456789', //d 100
        '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~' //s 115
    ];

    /**
     * Class constructor.
     *
     * <p>The class constructor do nothing, is present only for compatibility with Container.</p>
     */
    public function __construct()
    {
    }

    /**
     * Generates a random password.
     *
     * @param int $length Desiderated password length.
     *
     * @return string Random password.
     *
     * @todo Throw an exception if $length parameter is less than four
     */
    public function getFromRandom(int $length): string
    {
        $originalLength = $length;
        $password = [];

        while ($length--) {
            $password[] = $this->getRandomChar($this->chars[\random_int(0, 3)]);
        }

        $stringPassword = \implode($password);

        // check for all elments in topology
        $topology = $this->getTopology($stringPassword);
        $array = \str_split($topology);
        $unique = \array_unique($array);
        \sort($unique);

        // if the password doesn't contain elements from every group
        // go recursive to generate a new password
        if (\count(\array_diff(['d', 'l', 's', 'u'], $unique)) > 0) {
            return $this->getFromRandom($originalLength);
        }

        return $stringPassword;
    }

    /**
     * Returns topology for given password.
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
     * @param string $char The char for which get the topology group.
     *
     * @return string The character to identify the topology.
     *
     * @throws InvalidArgumentException If char provided isn't inside any group.
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
     * @param string $topology Topology to generate password.
     *
     * @return string Random password corresponding the given topology.
     *
     * @throws InvalidArgumentException If invalid pattern or void string is provided.
     */
    public function getFromTopology(string $topology): string
    {
        // check for void string
        if (\strlen($topology) === 0) {
            throw new InvalidArgumentException('Invalid pattern provided, accepted only u, l, d and s.');
        }

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
     * Get a random char from a string.
     *
     * @param string $interval The string where extract a random char.
     *
     * @return string The random char extracted from the string.
     */
    private function getRandomChar(string $interval): string
    {
        $size = \strlen($interval) - 1;
        $int = \random_int(0, \max(0, $size));

        return $interval[$int];
    }
}
