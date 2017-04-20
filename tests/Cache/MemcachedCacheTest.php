<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Cache\Exception\InvalidArgumentException;
use Linna\Cache\MemcachedCache;
use PHPUnit\Framework\TestCase;

class MemcachedCacheTest extends TestCase
{
    protected $memcached;

    protected $cache;

    public function setUp()
    {
        if (!class_exists('Memcached')) {
            return;
        }

        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        $this->memcached = $memcached;

        $this->cache = new MemcachedCache($memcached);
    }

    public function KeyProvider()
    {
        return [
            [1],
            [[0, 1]],
            [(object) [0, 1]],
            [1.5],
        ];
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testSetInvalidKey($key)
    {
        $this->cache->set($key, [0, 1, 2, 3, 4]);
    }

    public function testSet()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals([0, 1, 2, 3, 4], $this->memcached->get('foo'));
    }

    public function testSetTtl()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4], 10);

        $this->assertEquals([0, 1, 2, 3, 4], $this->memcached->get('foo'));
    }

    public function testSetTtlDateInterval()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4], new DateInterval('PT10S'));

        $this->assertEquals([0, 1, 2, 3, 4], $this->memcached->get('foo'));
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testGetInvalidKey($key)
    {
        $this->cache->get($key);
    }

    public function testGet()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals([0, 1, 2, 3, 4], $this->memcached->get('foo'));

        $this->assertEquals([0, 1, 2, 3, 4], $this->cache->get('foo'));
    }

    public function testGetDefault()
    {
        $this->assertEquals(null, $this->cache->get('foo_not_exist'));
    }

    public function testGetExpired()
    {
        $this->cache->clear();

        $this->cache->set('foo', [0, 1, 2, 3, 4], -10);

        $this->assertEquals(null, $this->cache->get('foo'));
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testDeleteInvalidKey($key)
    {
        $this->cache->delete($key);
    }

    public function testDeleteTrue()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, $this->cache->delete('foo'));
    }

    public function testDeleteFalse()
    {
        $this->assertEquals(false, $this->cache->delete('foo'));
    }

    public function testClear()
    {
        $this->cache->set('foo_0', [0]);
        $this->cache->set('foo_1', [1]);
        $this->cache->set('foo_2', [2]);
        $this->cache->set('foo_3', [3]);
        $this->cache->set('foo_4', [4]);
        $this->cache->set('foo_5', [5]);

        $this->cache->clear();

        $this->assertEquals(false, $this->cache->get('foo_0'));
        $this->assertEquals(false, $this->cache->get('foo_1'));
        $this->assertEquals(false, $this->cache->get('foo_2'));
        $this->assertEquals(false, $this->cache->get('foo_3'));
        $this->assertEquals(false, $this->cache->get('foo_4'));
        $this->assertEquals(false, $this->cache->get('foo_5'));
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testGetMultipleInvalidKey($key)
    {
        $this->cache->getMultiple($key);
    }

    public function testGetMultiple()
    {
        $this->cache->set('foo_0', [0]);
        $this->cache->set('foo_1', [1]);
        $this->cache->set('foo_2', [2]);
        $this->cache->set('foo_3', [3]);
        $this->cache->set('foo_4', [4]);
        $this->cache->set('foo_5', [5]);

        $keys = [
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ];

        $values = [
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ];

        $this->assertEquals($values, $this->cache->getMultiple($keys));

        $this->cache->clear();
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testSetMultipleInvalidKey($key)
    {
        $this->cache->SetMultiple($key);
    }

    public function testSetMultiple()
    {
        $this->cache->SetMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]);

        $keys = [
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ];

        $values = [
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ];

        $this->assertEquals($values, $this->cache->getMultiple($keys));

        $this->cache->clear();
    }

    public function testSetMultipleTtl()
    {
        $this->cache->SetMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], new DateInterval('PT10S'));

        $keys = [
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ];

        $values = [
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ];

        $this->assertEquals($values, $this->cache->getMultiple($keys));

        $this->cache->clear();
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testDeleteMultipleInvalidKey($key)
    {
        $this->cache->deleteMultiple($key);
    }

    public function testDeleteMultiple()
    {
        $this->cache->SetMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]);

        $keys = [
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ];

        $this->cache->deleteMultiple($keys);

        $this->assertEquals(false, $this->cache->get('foo_0'));
        $this->assertEquals(false, $this->cache->get('foo_1'));
        $this->assertEquals(false, $this->cache->get('foo_2'));
        $this->assertEquals(false, $this->cache->get('foo_3'));
        $this->assertEquals(false, $this->cache->get('foo_4'));
        $this->assertEquals(false, $this->cache->get('foo_5'));

        $this->cache->clear();
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException Linna\Cache\Exception\InvalidArgumentException
     */
    public function testHasInvalidKey($key)
    {
        $this->cache->Has($key);
    }

    public function testHasFalse()
    {
        $this->assertEquals(false, $this->cache->Has('foo_false'));
    }

    public function testHasTrue()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);
        $this->assertEquals(true, $this->cache->Has('foo'));

        $this->cache->clear();
    }

    public function testHasExpired()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4], -10);

        $this->assertEquals(false, $this->cache->has('foo'));
    }
}
