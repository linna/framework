<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use InvalidArgumentException;
use Linna\Authentication\PasswordGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Password Generator Test.
 */
class PasswordGeneratorTest extends TestCase
{
    /**
     * @var PasswordGenerator The password class.
     */
    protected static $passwordGenerator;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$passwordGenerator = new PasswordGenerator();
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$passwordGenerator = null;
    }

    /**
     * String length provider.
     *
     * @return array
     */
    public function stringLengthProvider(): array
    {
        return [
            [15],
            [20],
            [25],
            [30]
        ];
    }

    /**
     * Test get from random.
     *
     * @dataProvider stringLengthProvider
     *
     * @return void
     */
    public function testGetFromRandom(int $strLen): void
    {
        $password = self::$passwordGenerator->getFromRandom($strLen);

        $this->assertEquals($strLen, \strlen($password));
    }

    /**
     * Test get from random.
     *
     * @dataProvider stringLengthProvider
     *
     * @return void
     */
    public function testCheckRandomTopology(int $strLen): void
    {
        $topology = '';

        while (true) {
            $topology = self::$passwordGenerator->getTopology(self::$passwordGenerator->getFromRandom($strLen));

            $presU = \strpos($topology, 'u');
            $presL = \strpos($topology, 'l');
            $presD = \strpos($topology, 'd');
            $presS = \strpos($topology, 's');

            if ($presU !== false &&  $presL !== false && $presD !== false && $presS !== false) {
                break;
            }
        }

        $this->assertTrue((\strpos($topology, 'u') === false) ? false : true);
        $this->assertTrue((\strpos($topology, 'l') === false) ? false : true);
        $this->assertTrue((\strpos($topology, 'd') === false) ? false : true);
        $this->assertTrue((\strpos($topology, 's') === false) ? false : true);
    }

    /**
     * Test in range edges.
     *
     * @return void
     */
    public function testCheckRangesEdges(): void
    {
        $this->assertEquals('ssddssuussllss', self::$passwordGenerator->getTopology('!/09:@AZ[`az{~'));
    }

    /**
     * Topology and passwords provider.
     *
     * @return array
     */
    public function topologyAndPasswordProvider(): array
    {
        $array = [];

        for ($i = 0; $i < 10; $i++) {
            $password = (new PasswordGenerator())->getFromRandom(20);
            $topology = (new PasswordGenerator())->getTopology($password);

            $array[] = [$password, $topology];
        }

        return $array;
    }

    /**
     * Test get topology.
     *
     * @dataProvider topologyAndPasswordProvider
     *
     * @return void
     */
    public function testGetTopology(string $password, string $topology): void
    {
        $this->assertEquals($topology, self::$passwordGenerator->getTopology($password));
    }

    /**
     * Test get topology exception.
     *
     * @return void
     */
    public function testGetTopologyException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Out of group character provided.");

        self::$passwordGenerator->getTopology('abcdefgà');
    }

    /**
     * Topology provider.
     *
     * @return array
     */
    public function topologyProvider(): array
    {
        $array = [];

        for ($i = 0; $i < 10; $i++) {
            $password = (new PasswordGenerator())->getFromRandom(20);
            $array[] = [(new PasswordGenerator())->getTopology($password)];
        }

        return $array;
    }

    /**
     * Test get topology.
     *
     * @dataProvider topologyProvider
     *
     * @return void
     */
    public function testGetFromTopology(string $topology): void
    {
        $password = self::$passwordGenerator->getFromTopology($topology);
        $this->assertEquals($topology, self::$passwordGenerator->getTopology($password));
    }

    /**
     * Bad topology provider.
     *
     * @return array
     */
    public function badTopologyProvider(): array
    {
        return [
           ['uldz'],
           ['uld!'],
           ['uld1'],
           ['...'],
           [' '],
           ['']
       ];
    }

    /**
     * Test get topology.
     *
     * @dataProvider badTopologyProvider
     *
     * @return void
     */
    public function testGetFromTopologyException(string $topology): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid pattern provided, accepted only u, l, d and s.");

        self::$passwordGenerator->getFromTopology($topology);
    }
}
