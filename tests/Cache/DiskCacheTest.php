<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Cache\DiskCache;
use Linna\Cache\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DiskCacheTest extends TestCase
{
    protected $cache;

    protected $cacheSerialize;

    public function setUp()
    {
        $this->cache = new DiskCache();

        $this->cacheSerialize = new DiskCache(['serialize' => true]);
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
     */
    public function testSetInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->cache->set($key, [0, 1, 2, 3, 4]);
    }

    public function testSet()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo').'.php'));
    }

    public function testSetSerialize()
    {
        $this->cacheSerialize->set('foo_serialize', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_serialize').'.php'));
    }

    public function testSetTtlNull()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4]);

        $cacheValue = include '/tmp/'.sha1('foo_ttl').'.php';

        $this->assertEquals(null, $cacheValue['expires']);
    }

    public function testSetTtl()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4], 10);

        $cacheValue = include '/tmp/'.sha1('foo_ttl').'.php';

        $this->assertEquals(true, ($cacheValue['expires'] > time()));
    }

    public function testSetTtlDateInterval()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4], new DateInterval('PT10S'));

        $cacheValue = include '/tmp/'.sha1('foo_ttl').'.php';

        $this->assertEquals(true, ($cacheValue['expires'] > time()));
    }

    /**
     * @dataProvider KeyProvider
     */
    public function testGetInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->cache->get($key);
    }

    public function testGet()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo').'.php'));

        $this->assertEquals([0, 1, 2, 3, 4], $this->cache->get('foo'));
    }

    public function testGetSerialize()
    {
        $this->cacheSerialize->set('foo_serialize', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_serialize').'.php'));

        $this->assertEquals([0, 1, 2, 3, 4], $this->cacheSerialize->get('foo_serialize'));
    }

    public function testGetDefault()
    {
        $this->assertEquals(null, $this->cache->get('foo_not_exist'));
    }

    public function testGetExpired()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4], -10);

        $this->assertEquals(null, $this->cache->get('foo'));
    }

    /**
     * @dataProvider KeyProvider
     */
    public function testDeleteInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
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

        $this->assertEquals([], glob('tmp/*.php'));
    }

    /**
     * @dataProvider KeyProvider
     */
    public function testGetMultipleInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
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

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_0').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_1').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_2').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_3').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_4').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_5').'.php'));

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
     */
    public function testSetMultipleInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
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

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_0').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_1').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_2').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_3').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_4').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_5').'.php'));

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

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_0').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_1').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_2').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_3').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_4').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_5').'.php'));

        $this->cache->clear();
    }

    /**
     * @dataProvider KeyProvider
     */
    public function testDeleteMultipleInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
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

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_0').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_1').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_2').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_3').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_4').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_5').'.php'));

        $keys = [
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ];

        $this->cache->deleteMultiple($keys);

        $this->assertEquals(false, file_exists('/tmp/'.sha1('foo_0').'.php'));
        $this->assertEquals(false, file_exists('/tmp/'.sha1('foo_1').'.php'));
        $this->assertEquals(false, file_exists('/tmp/'.sha1('foo_2').'.php'));
        $this->assertEquals(false, file_exists('/tmp/'.sha1('foo_3').'.php'));
        $this->assertEquals(false, file_exists('/tmp/'.sha1('foo_4').'.php'));
        $this->assertEquals(false, file_exists('/tmp/'.sha1('foo_5').'.php'));

        $this->cache->clear();
    }

    /**
     * @dataProvider KeyProvider
     */
    public function testHasInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
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
