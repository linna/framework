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
        self::$cache = new MemcachedCache(['host' => $GLOBALS['mem_host'], 'port' => (int) $GLOBALS['mem_port']]);
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
     * Invalid options provider.
     *
     * @return array
     */
    public static function invalidOptionsProvider(): array
    {
        return [
            [[]],                      //void options
            [['host' => '127.0.0.1']], //only host
            [['port' => 11211]],       //only port
        ];
    }

    /**
     * Test create instance without options.
     *
     * @dataProvider invalidOptionsProvider
     *
     * @return void
     */
    public function testCreateInstanceWithoutOptions($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Somethig went worng adding memcached servers.");

        (new MemcachedCache($options));
    }

    /**
     * Options provider.
     *
     * @return array
     */
    public static function optionsProvider(): array
    {
        return [
            [['host' => $GLOBALS['mem_host'], 'port' => (int) $GLOBALS['mem_port']]],                 //default weight
            [['host' => $GLOBALS['mem_host'], 'port' => (int) $GLOBALS['mem_port'], 'weight' => 10]], //custom wight
            [['servers'=>[[$GLOBALS['mem_host'], (int) $GLOBALS['mem_port']]]]]                       //server list
        ];
    }

    /**
     * Test create instance without options.
     *
     * @dataProvider optionsProvider
     *
     * @return void
     */
    public function testCreateInstance($options): void
    {
        $cache = new MemcachedCache($options);
        $this->assertTrue($cache->set('foo', 1));

        $this->assertSame(1, $cache->get('foo'));
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
    /*public function testGetWithExpiredElement(): void
    {
        $this->assertFalse(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertNull(self::$cache->get('foo'));
    }*/

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
    /*public function testHasWithExpiredElement(): void
    {
        $this->assertFalse(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertFalse(self::$cache->has('foo'));
    }*/
}
