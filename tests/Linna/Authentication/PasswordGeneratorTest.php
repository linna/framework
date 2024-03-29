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
use PHPUnit\Framework\TestCase;
use ReflectionObject;

/**
 * Password Generator Test.
 */
class PasswordGeneratorTest extends TestCase
{
    /** @var PasswordGenerator The password class. */
    protected static PasswordGenerator $passwordGenerator;

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
        //self::$passwordGenerator = null;
    }

    /**
     * String length provider.
     *
     * @return array
     */
    public static function stringLengthProvider(): array
    {
        return [
            [4],
            [8],
            [16],
            [24],
            [32],
            [40]
        ];
    }

    /**
     * Test get from random.
     *
     * @dataProvider stringLengthProvider
     *
     * @param int $strLen
     *
     * @return void
     */
    public function testGetFromRandom(int $strLen): void
    {
        $password = self::$passwordGenerator->getFromRandom($strLen);
        $topology = self::$passwordGenerator->getTopology($password);

        $array = \str_split($topology);
        $unique = \array_unique($array);
        \sort($unique);

        $this->assertEquals($strLen, \strlen($password));
        $this->assertSame(['d', 'l', 's', 'u'], $unique);
    }

    /**
     * Test get from random.
     *
     * @dataProvider stringLengthProvider
     *
     * @param int $strLen
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
    public static function topologyAndPasswordProvider(): array
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
     * @param string $password
     * @param string $topology
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
    public static function topologyProvider(): array
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
     * @param string $topology
     *
     * @return void
     */
    public function testGetFromTopology(string $topology): void
    {
        $password = self::$passwordGenerator->getFromTopology(\strtoupper($topology));
        $this->assertEquals($topology, self::$passwordGenerator->getTopology($password));
    }

    /**
     * Bad topology provider.
     *
     * @return array
     */
    public static function badTopologyProvider(): array
    {
        return [
           ['uldz'], //invalid char
           ['uld!'], //invalid char
           ['uld1'], //invalid char
           ['...'],  //invalid char
           ['   '],  //only spaces
           [' '],    //one space
           ['']      //void string
       ];
    }

    /**
     * Test get topology.
     *
     * @dataProvider badTopologyProvider
     *
     * @param string $topology
     *
     * @return void
     */
    public function testGetFromTopologyException(string $topology): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid pattern provided, accepted only u, l, d and s.");

        self::$passwordGenerator->getFromTopology($topology);
    }

    /**
     * Test private method getRandomChar.
     */
    public function testInternalGetRandomChar()
    {
        //password generator instance
        $object = new PasswordGenerator();
        //reflection for the object
        $reflector = new ReflectionObject($object);
        //get private method
        $method = $reflector->getMethod('getRandomChar');
        //change visibility
        $method->setAccessible(true);

        $this->assertSame('a', $method->invoke($object, 'a'));

        $string = 'abcdefghijklmnopqrstuvwxyz';
        $expected = \str_split($string);

        //burn cpu using infection
        while (\count($expected) > 0) {
            $char = $method->invoke($object, $string);
            if (($key = \array_search($char, $expected)) !== false) {
                unset($expected[$key]);
            }
        }

        $this->assertCount(0, $expected);
    }
}
