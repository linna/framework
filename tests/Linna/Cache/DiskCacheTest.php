<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use PHPUnit\Framework\TestCase;

/**
 * Disk Cache Driver test.
 */
class DiskCacheTest extends TestCase
{
    use CacheTrait;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$cache = new DiskCache();
    }

    /**
     * Setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        self::$cache->clear();
    }

    /**
     * Test create instance with options.
     *
     * @return void
     */
    public function testCreateInstance(): void
    {
        $cache = new DiskCache();
        $this->assertInstanceOf(DiskCache::class, $cache);
    }

    /**
     * Test check file deletion with expired element.
     *
     * @return void
     */
    public function testCheckFileDeletionWithDeleteExistingElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));
        $this->assertTrue(self::$cache->delete('foo'));
        $this->assertNull(self::$cache->get('foo'));
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo').'.php');
    }

    /**
     * Test check file deletion with expired element.
     *
     * @return void
     */
    public function testCheckFileDeletionWithExpiredElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4], 1));

        \usleep(1000500);

        $this->assertNull(self::$cache->get('foo'));
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo').'.php');
    }
}
