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

use InvalidArgumentException;
use Linna\Cache\MemcachedCache;
use Memcached;
use PHPUnit\Framework\TestCase;
use TypeError;

class MemcachedCacheTest extends TestCase
{
    /**
     * @var MemcachedCache Memcached Cache resource
     */
    protected static $cache;

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
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$cache = null;
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
     * @return void
     */
    public function testCreateInstanceWithoutResource($resource): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("MemcachedCache class need instance of Memcached passed as option. ['resource' => \$memcached].");

        (new MemcachedCache(['resource' => $resource]));
    }

    /**
     * Invalid key provider.
     *
     * @return array
     */
    public function invalidKeyProvider(): array
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
     * Test set with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testSetWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->set($key, [0, 1, 2, 3, 4]);
    }

    /**
     * Test set.
     *
     * @return void
     */
    public function testSet(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        usleep(2000100);

        $this->assertTrue(self::$cache->has('foo'));
    }

    /**
     * Test set with ttl null.
     *
     * @return void
     */
    public function testSetWithTtlAtZero(): void
    {
        $this->assertTrue(self::$cache->set('foo_ttl', [0, 1, 2, 3, 4], 0));

        usleep(1000100);

        $this->assertTrue(self::$cache->has('foo_ttl'));
    }

    /**
     * Test set with ttl value.
     *
     * @return void
     */
    public function testSetWithTtl(): void
    {
        $this->assertTrue(self::$cache->set('foo_ttl', [0, 1, 2, 3, 4], 1));

        usleep(1000100);

        $this->assertNull(self::$cache->get('foo_ttl'));
    }

    /**
     * Test get with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testGetWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->get($key);
    }

    /**
     * Test get.
     *
     * @return void
     */
    public function testGet(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertEquals([0, 1, 2, 3, 4], self::$cache->get('foo'));
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
     * Test delete with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testDeleteWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->delete($key);
    }

    /**
     * Test delete an existing element.
     *
     * @return void
     */
    public function testDeleteExistingElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertTrue(self::$cache->delete('foo'));
    }

    /**
     * Test delete not existing element.
     *
     * @return void
     */
    public function testDeleteNotExistingElement(): void
    {
        $this->assertFalse(self::$cache->delete('foo'));
    }

    /**
     * Test clear all cache.
     *
     * @return void
     */
    public function testClear(): void
    {
        $this->assertTrue(self::$cache->set('foo_0', [0]));
        $this->assertTrue(self::$cache->set('foo_1', [1]));
        $this->assertTrue(self::$cache->set('foo_2', [2]));
        $this->assertTrue(self::$cache->set('foo_3', [3]));
        $this->assertTrue(self::$cache->set('foo_4', [4]));
        $this->assertTrue(self::$cache->set('foo_5', [5]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertTrue(self::$cache->clear());

        $this->assertFalse(self::$cache->has('foo_0'));
        $this->assertFalse(self::$cache->has('foo_1'));
        $this->assertFalse(self::$cache->has('foo_2'));
        $this->assertFalse(self::$cache->has('foo_3'));
        $this->assertFalse(self::$cache->has('foo_4'));
        $this->assertFalse(self::$cache->has('foo_5'));
    }

    /**
     * Test get multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testGetMultipleWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->getMultiple($key);
    }

    /**
     * Test get multiple elements.
     *
     * @return void
     */
    public function testGetMultiple(): void
    {
        $this->assertTrue(self::$cache->set('foo_0', [0]));
        $this->assertTrue(self::$cache->set('foo_1', [1]));
        $this->assertTrue(self::$cache->set('foo_2', [2]));
        $this->assertTrue(self::$cache->set('foo_3', [3]));
        $this->assertTrue(self::$cache->set('foo_4', [4]));
        $this->assertTrue(self::$cache->set('foo_5', [5]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertEquals([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], self::$cache->getMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));
    }

    /**
     * Test set multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testSetMultipleWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->setMultiple($key);
    }

    /**
     * Test set multiple elements.
     *
     * @return void
     */
    public function testSetMultiple(): void
    {
        $this->assertTrue(self::$cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertEquals([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], self::$cache->getMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));
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

        usleep(1000100);

        $this->assertNull(self::$cache->get('foo_0'));
        $this->assertNull(self::$cache->get('foo_1'));
        $this->assertNull(self::$cache->get('foo_2'));
        $this->assertNull(self::$cache->get('foo_3'));
        $this->assertNull(self::$cache->get('foo_4'));
        $this->assertNull(self::$cache->get('foo_5'));
    }

    /**
     * Teset delete multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testDeleteMultipleWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->deleteMultiple($key);
    }

    /**
     * Test delete multiple elements.
     *
     * @return void
     */
    public function testDeleteMultiple(): void
    {
        $this->assertTrue(self::$cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertTrue(self::$cache->deleteMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));

        $this->assertFalse(self::$cache->has('foo_0'));
        $this->assertFalse(self::$cache->has('foo_1'));
        $this->assertFalse(self::$cache->has('foo_2'));
        $this->assertFalse(self::$cache->has('foo_3'));
        $this->assertFalse(self::$cache->has('foo_4'));
        $this->assertFalse(self::$cache->has('foo_5'));
    }

    /**
     * Test has with invalid key.
     *
     * @dataProvider invalidKeyProvider
     *
     * @return void
     */
    public function testHasWithInvalidKey($key): void
    {
        $this->expectException(TypeError::class);

        self::$cache->has($key);
    }

    /**
     * Test has with existing element.
     *
     * @return void
     */
    public function testHasExistingElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertTrue(self::$cache->has('foo'));
    }

    /**
     * Test has with not existing element.
     *
     * @return void
     */
    public function testHasNotExistingElement(): void
    {
        $this->assertFalse(self::$cache->has('foo_false'));
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
