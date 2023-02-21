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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Redis Cache Driver test.
 */
class RedisCacheTest extends TestCase
{
    use CacheTrait;

    /**
     * Set up before class.
     *
     * @requires extension redis
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $options = ['connect' => ['host' => $GLOBALS['redis_host'], 'port' => (int) $GLOBALS['redis_port'], 'timeout' => 5]];

        if (\strlen($GLOBALS['redis_password']) > 0) {
            $options['auth'] = ['pass' => $GLOBALS['redis_password']];
        }

        self::$cache = new RedisCache($options);
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
        self::$cache->clear();
    }

    /**
     * Options provider.
     *
     * @return array
     */
    public static function optionsProvider(): array
    {
        $options = ['connect' => ['host' => $GLOBALS['redis_host'], 'port' => (int) $GLOBALS['redis_port'], 'timeout' => 5]];

        if (\strlen($GLOBALS['redis_password']) > 0) {
            $options['auth'] = ['pass' => $GLOBALS['redis_password']];
        }

        return [
            [$options], //default host and port
        ];
    }

    /**
     * Invalid options provider.
     *
     * @return array
     */
    public static function invalidOptionsProvider(): array
    {
        return [
            [[]],                              //void options
            [['connect' => ['port' => 6379]]], //only port
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
        $this->expectExceptionMessage('Unable to connect to Redis server.');

        (new RedisCache($options));
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

        /*$this->assertTrue($cache->set('foo_string', 'a'));
        $this->assertTrue($cache->set('foo_int', 1));

        $this->assertTrue($cache->has('foo_string'));
        $this->assertTrue($cache->has('foo_int'));

        $this->assertSame('a', $cache->get('foo_string'));
        $this->assertSame(1, $cache->get('foo_int'));

        $this->assertNull($cache->get('foo_not_exists'));

        $this->assertTrue($cache->delete('foo_string'));
        $this->assertTrue($cache->delete('foo_int'));

        $this->assertFalse($cache->delete('foo_string'));
        $this->assertFalse($cache->delete('foo_int'));

        $this->assertFalse($cache->has('foo_string'));
        $this->assertFalse($cache->has('foo_int'));

        $this->assertNull($cache->get('foo_string'));
        $this->assertNull($cache->get('foo_int'));*/
    }
}
