<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
     * @var array Characters intervall utf8 dec rappresentation
     */
    private $chars = [
        [[65, 90]], //u 117
        [[97, 122]], //l 108
        [[48, 57]], //d 100
        [[33, 47], [58, 64], [91, 96], [123, 126]], //s 115
    ];

    /**
     * @var array Characters utf8 dec rappresentation
     */
    private $groups = [
        117 => 0, //u
        108 => 1, //l
        100 => 2, //d
        115 => 3  //s
    ];

    
    /**
     * Generate a random password.
     * <pre><code class="php">use Linna\Auth\PasswordGenerator;
     *
     * $passwordGenerator = new PasswordGenerator();
     * $random = $passwordGenerator->getFromRandom(20);
     *
     * //var_dump result
     * //r4Q,1J*tM7D_99q0u>61
     * var_dump($random);
     * </code></pre>
     *
     * @param int $length Desiderated password length.
     * @return string Random password.
     */
    public function getFromRandom(int $length): string
    {
        $password = [];

        while ($length) {
            $password[] = $this->getRandomChar($this->chars[random_int(0, 3)]);
            
            $length--;
        }
        
        return implode($password);
    }

    /**
     * Return topology for given password.
     * <pre><code class="php">use Linna\Auth\PasswordGenerator;
     *
     * $passwordGenerator = new PasswordGenerator();
     * $topology = $passwordGenerator->getTopology('r4Q,1J*tM7D_99q0u>61');
     *
     * //var_dump result
     * //ldusdusludusddldlsdd
     * var_dump($topology);
     * </code></pre>
     * @param string $password Password.
     *
     * @return string Topology for the argument passed password.
     */
    public function getTopology(string $password): string
    {
        $array = str_split($password);
        $topology = [];

        foreach ($array as $char) {
            $int = ord($char);

            if ($this->inRanges($int, $this->chars[0])) {
                $topology[] = 'u';
                continue;
            }

            if ($this->inRanges($int, $this->chars[1])) {
                $topology[] = 'l';
                continue;
            }

            if ($this->inRanges($int, $this->chars[2])) {
                $topology[] = 'd';
                continue;
            }

            if ($this->inRanges($int, $this->chars[3])) {
                $topology[] = 's';
                continue;
            }
        }

        return implode($topology);
    }

    /**
     * Generate a random password corresponding at the given topology.
     * <pre><code class="php">use Linna\Auth\PasswordGenerator;
     *
     * $passwordGenerator = new PasswordGenerator();
     * $random = $passwordGenerator->getFromTopology('ldusdusludusddldlsdd');
     *
     * //var_dump result
     * //r4Q,1J*tM7D_99q0u>61
     * var_dump($random);
     * </code></pre>
     *
     * @param string $topology Topology for generate password.
     * @return string Random password corresponding the given topology.
     *
     * @throws InvalidArgumentException If invalid pattern is provided.
     */
    public function getFromTopology(string $topology): string
    {
        $array = str_split(strtolower($topology));
        $password = [];

        foreach ($array as $char) {
            $int = ord($char);

            if (isset($this->groups[$int])) {
                $key = $this->groups[$int];
                $password[] = $this->getRandomChar($this->chars[$key]);

                continue;
            }

            throw new InvalidArgumentException('Invalid pattern provided, accepted only u, l, d and s');
        }

        return implode($password);
    }

    /**
     * Get random char between.
     *
     * @param array $interval
     * @return string
     */
    private function getRandomChar(array $interval): string
    {
        $int = random_int(33, 122);
        
        while (true) {
            if ($this->inRanges($int, $interval)) {
                break;
            }
        }

        return chr($int);
    }

    /**
     * Check if value is between given range.
     *
     * @param mixed $value
     * @param array $ranges
     *
     * @return bool
     */
    private function inRanges($value, array $ranges): bool
    {
        foreach ($ranges as $range) {
            if ($value >= $range[0] && $value <= $range[1]) {
                return true;
            }
        }

        return false;
    }
}
