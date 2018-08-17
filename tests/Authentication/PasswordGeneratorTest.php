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
    protected $password;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $this->password = new PasswordGenerator();
    }

    /**
     * String length provider.
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
     */
    public function testGetFromRandom(int $strLen): void
    {
        $password = $this->password->getFromRandom($strLen);

        $this->assertEquals($strLen, strlen($password));
    }

    /**
     * Test get from random.
     *
     * @dataProvider stringLengthProvider
     */
    public function testCheckRandomTopology(int $strLen): void
    {
        $topology = '';

        while (true) {
            $topology = $this->password->getTopology($this->password->getFromRandom($strLen));

            $presU = strpos($topology, 'u');
            $presL = strpos($topology, 'l');
            $presD = strpos($topology, 'd');
            $presS = strpos($topology, 's');

            if ($presU !== false &&  $presL !== false && $presD !== false && $presS !== false) {
                break;
            }
        }

        $this->assertTrue((strpos($topology, 'u') === false) ? false : true);
        $this->assertTrue((strpos($topology, 'l') === false) ? false : true);
        $this->assertTrue((strpos($topology, 'd') === false) ? false : true);
        $this->assertTrue((strpos($topology, 's') === false) ? false : true);
    }

    /**
     * Test in range edges.
     */
    public function testCheckRangesEdges(): void
    {
        $this->assertEquals('ssddssuussllss', (new PasswordGenerator())->getTopology('!/09:@AZ[`az{~'));
    }

    /**
     * Topology and passwords provider.
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
     */
    public function testGetTopology(string $password, string $topology): void
    {
        $this->assertEquals($topology, $this->password->getTopology($password));
    }

    /**
     * Bad topology provider.
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
     * @expectedException InvalidArgumentException
     */
    public function testGetTopologyWithBadTopology(string $topology): void
    {
        $this->password->getFromTopology($topology);
    }

    /**
     * Topology provider.
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
     */
    public function testGetFromTopology(string $topology): void
    {
        $password = $this->password->getFromTopology($topology);
        $this->assertEquals($topology, $this->password->getTopology($password));
    }
}
