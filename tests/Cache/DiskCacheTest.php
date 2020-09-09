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

use Linna\Cache\DiskCache;
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
     * Test get with default value.
     *
     * @return void
     */
    public function testGetWithDefault(): void
    {
        $this->assertNull(self::$cache->get('foo_not_exist'));

        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_not_exist').'.php');
    }

    /**
     * Test get with expired element.
     *
     * @return void
     */
    public function testGetWithExpiredElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertNull(self::$cache->get('foo'));

        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo').'.php');
    }

    /**
     * Test set multiple elements with ttl.
     *
     * @return void
     */
    public function testSetMultipleTtl(): void
    {
        $this->assertTrue(self::$cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], 1));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        \usleep(1000100);

        $this->assertNull(self::$cache->get('foo_0'));
        $this->assertNull(self::$cache->get('foo_1'));
        $this->assertNull(self::$cache->get('foo_2'));
        $this->assertNull(self::$cache->get('foo_3'));
        $this->assertNull(self::$cache->get('foo_4'));
        $this->assertNull(self::$cache->get('foo_5'));

        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_0').'.php');
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_1').'.php');
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_2').'.php');
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_3').'.php');
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_4').'.php');
        $this->assertFileDoesNotExist('/tmp/'.\sha1('foo_5').'.php');
    }

    /**
     * Test has with expired element.
     *
     * @return void
     */
    public function testHasWithExpiredElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertFalse(self::$cache->has('foo'));
    }
}
