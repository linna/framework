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

/**
 * Memecached Cache Driver test.
 */
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
        $this->assertInstanceOf(MemcachedCache::class, $cache);
    }
}
