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

use Linna\DataMapper\UUID4;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * Universally Unique Identifier Version 4 utility Test
 */
class UUIDTest extends TestCase
{
    /**
     * Test new object instance.
     *
     * @return void
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(UUID4::class, new UUID4());
        $this->assertInstanceOf(UUID4::class, new UUID4('691ce06d-0afb-428d-b5fc-5384cd3046fb'));
    }

    /**
     * Test new object instance with worng UUID.
     *
     * @return void
     */
    public function testNewObjectInstanceWithWrongUUID(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID version 4 provided.');

        (new UUID4('691ce06d-0afb-428d-b5fc'));
    }

    /**
     * Test UUID with get hex.
     *
     * @return void
     */
    public function testUUIDWithGetHex(): void
    {
        $uuid = new UUID4('691ce06d-0afb-428d-b5fc-5384cd3046fb');

        $this->assertSame('691ce06d-0afb-428d-b5fc-5384cd3046fb', $uuid->getHex());
    }

    /**
     * Test UUID with get bin.
     *
     * @return void
     */
    public function testUUIDWithGetBin(): void
    {
        $uuid = new UUID4('691ce06d-0afb-428d-b5fc-5384cd3046fb');

        $this->assertSame(hex2bin(str_replace('-', '', '691ce06d-0afb-428d-b5fc-5384cd3046fb')), $uuid->getBin());
    }

    /**
     * Test generate with get hex.
     *
     * @return void
     */
    public function testGenerateWithGetHex(): void
    {
        $uuid = new UUID4();
        $regex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

        $this->assertRegExp($regex, $uuid->getHex());
        $this->assertRegExp($regex, strtoupper($uuid->getHex()));
    }

    /**
     * Test generate with get bin.
     *
     * @return void
     */
    public function testGenerateWithGetBin(): void
    {
        $uuid = new UUID4();

        $this->assertSame(hex2bin(str_replace('-', '', $uuid->getHex())), $uuid->getBin());
    }
}
