<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

use InvalidArgumentException;
//use Linna\Cache\MemcachedCache;
use Memcached;
use PHPUnit\Framework\TestCase;

class MemcachedCacheTest extends TestCase
{
    use CacheTrait;

    /**
     * Set up before class.
     *
     * @requires extension memcached
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $memcached = new Memcached();
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        self::$cache = new MemcachedCache(['resource' => $memcached]);
    }

    /**
     * Setup.
     *
     * @requires extension memcached
     *
     * @return void
     */
    public function setUp(): void
    {
        self::$cache->clear();
    }

    /**
     * Invalid resource provider.
     *
     * @return array
     */
    public function invalidResourceProvider(): array
    {
        return [
            [1],
            [[0, 1]],
            [(object) [0, 1]],
            [1.5],
            [true]
        ];
    }

    /**
     * Test create instance without memcached resource.
     *
     * @dataProvider invalidResourceProvider
     *
     * @param mixed $resource
     *
     * @return void
     */
    public function testCreateInstanceWithoutResource($resource): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("MemcachedCache class need instance of Memcached passed as option. ['resource' => \$memcached].");

        (new MemcachedCache(['resource' => $resource]));
    }

    /**
     * Test get with default value.
     *
     * @return void
     */
    public function testGetWithDefault(): void
    {
        $this->assertNull(self::$cache->get('foo_not_exist'));
    }

    /**
     * Test get with expired element.
     *
     * @return void
     */
    public function testGetWithExpiredElement(): void
    {
        $this->assertFalse(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertNull(self::$cache->get('foo'));
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
    }

    /**
     * Test has with expired element.
     *
     * @return void
     */
    public function testHasWithExpiredElement(): void
    {
        $this->assertFalse(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertFalse(self::$cache->has('foo'));
    }
}
