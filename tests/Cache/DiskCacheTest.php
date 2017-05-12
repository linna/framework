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
use PHPUnit\Framework\TestCase;

class DiskCacheTest extends TestCase
{
    protected $cache;

    public function setUp()
    {
        $this->cache = new DiskCache();
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
     * @expectedException TypeError
     */
    public function testSetInvalidKey($key)
    {
        $this->cache->set($key, [0, 1, 2, 3, 4]);
    }

    public function testSet()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo').'.php'));
    }

    public function testSetTtlNull()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4]);

        $cacheValue = include '/tmp/'.sha1('foo_ttl').'.php';

        $this->assertEquals(0, $cacheValue['expires']);
    }

    public function testSetTtl()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4], 10);

        $cacheValue = include '/tmp/'.sha1('foo_ttl').'.php';

        $expectedTtl = time() + 10;

        $this->assertEquals($expectedTtl, $cacheValue['expires']);
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException TypeError
     */
    public function testGetInvalidKey($key)
    {
        $this->cache->get($key);
    }

    public function testGet()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo').'.php'));

        $this->assertEquals([0, 1, 2, 3, 4], $this->cache->get('foo'));
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
     * @expectedException TypeError
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

        $this->assertEquals([], glob('tmp/*.php'));
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException TypeError
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
     * @expectedException TypeError
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
        ], 10);

        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_0').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_1').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_2').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_3').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_4').'.php'));
        $this->assertEquals(true, file_exists('/tmp/'.sha1('foo_5').'.php'));

        $expectedTtl = time() + 10;

        $cacheValue0 = include '/tmp/'.sha1('foo_0').'.php';
        $cacheValue1 = include '/tmp/'.sha1('foo_1').'.php';
        $cacheValue2 = include '/tmp/'.sha1('foo_2').'.php';
        $cacheValue3 = include '/tmp/'.sha1('foo_3').'.php';
        $cacheValue4 = include '/tmp/'.sha1('foo_4').'.php';
        $cacheValue5 = include '/tmp/'.sha1('foo_5').'.php';

        $this->assertEquals($expectedTtl, $cacheValue0['expires']);
        $this->assertEquals($expectedTtl, $cacheValue1['expires']);
        $this->assertEquals($expectedTtl, $cacheValue2['expires']);
        $this->assertEquals($expectedTtl, $cacheValue3['expires']);
        $this->assertEquals($expectedTtl, $cacheValue4['expires']);
        $this->assertEquals($expectedTtl, $cacheValue5['expires']);

        $this->cache->clear();
    }

    /**
     * @dataProvider KeyProvider
     * @expectedException TypeError
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
     * @expectedException TypeError
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
