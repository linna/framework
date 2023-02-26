<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * Universally Unique Identifier Version 4 utility Test
 */
class Uuid4Test extends TestCase
{
    /**
     * Test new object instance.
     *
     * @return void
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(Uuid4::class, new Uuid4());
        $this->assertInstanceOf(Uuid4::class, new Uuid4('691ce06d-0afb-428d-b5fc-5384cd3046fb'));
    }

    /**
     * Test new object instance with worng Uuid.
     *
     * @return void
     */
    public function testNewObjectInstanceWithWrongUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID version 4 provided.');

        (new Uuid4('691ce06d-0afb-428d-b5fc'));
    }

    /**
     * Test Uuid4 with get hex.
     *
     * @return void
     */
    public function testUuid4WithGetHex(): void
    {
        $uuid = new Uuid4('691ce06d-0afb-428d-b5fc-5384cd3046fb');

        $this->assertSame('691ce06d-0afb-428d-b5fc-5384cd3046fb', $uuid->getHex());
    }

    /**
     * Test Uuid4 with get bin.
     *
     * @return void
     */
    public function testUuid4WithGetBin(): void
    {
        $uuid = new Uuid4('691ce06d-0afb-428d-b5fc-5384cd3046fb');

        $this->assertSame(\hex2bin(\str_replace('-', '', '691ce06d-0afb-428d-b5fc-5384cd3046fb')), $uuid->getBin());
    }

    /**
     * Test generate with get hex.
     *
     * @return void
     */
    public function testGenerateWithGetHex(): void
    {
        $uuid = new Uuid4();
        $regex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

        $this->assertMatchesRegularExpression($regex, $uuid->getHex());
        $this->assertMatchesRegularExpression($regex, \strtoupper($uuid->getHex()));
    }

    /**
     * Test generate with get bin.
     *
     * @return void
     */
    public function testGenerateWithGetBin(): void
    {
        $uuid = new Uuid4();

        $this->assertSame(\hex2bin(\str_replace('-', '', $uuid->getHex())), $uuid->getBin());
    }
}
