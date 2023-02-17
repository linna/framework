<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2023, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

//use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RedisCacheTest extends TestCase
{
    //use CacheTrait;

    /**
     * Set up before class.
     *
     * @requires extension redis
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        //self::$cache = new RedisCache(['host' => '127.0.0.1', 'port' => 6379]);
    }

    /**
     * Setup.
     *
     * @requires extension redis
     *
     * @return void
     */
    public function setUp(): void
    {
        //self::$cache->clear();
    }

    /**
     * Options provider.
     *
     * @return array
     */
    public static function optionsProvider(): array
    {
        return [
            [['host' => '127.0.0.1', 'port' => 6379]],                 //default host and port
            [['host' => '127.0.0.1', 'port' => 6379, 'timeout' => 5]], //timeout
        ];
    }

    /**
     * Test create instance with options.
     *
     * @dataProvider optionsProvider
     *
     * @return void
     */
    public function testCreateInstance($options): void
    {
        $cache = new RedisCache($options);
        $this->assertInstanceOf(RedisCache::class, $cache);
    }
}
