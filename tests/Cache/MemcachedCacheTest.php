<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Cache\MemcachedCache;
use PHPUnit\Framework\TestCase;

class MemcachedCacheTest extends TestCase
{
    /**
     * @var MemcachedCache Memcached Cache resource
     */
    private $cache = null;

    /**
     * Setup.
     *
     * @requires extension memcached
     */
    public function setUp()
    {
        $memcached = new Memcached();
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        $this->cache = new MemcachedCache(['resource' => $memcached]);
        $this->cache->clear();
    }
    
    /**
     * Invalid resource provider.
     *
     * @return array
     */
    public function invalidResourceProvider() : array
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
     * Test create instance without memcachedresource
     *
     * @dataProvider invalidResourceProvider
     * @expectedException InvalidArgumentException
     */
    public function testCreateInstanceWithoutResource($resource)
    {
        (new MemcachedCache(['resource' => $resource]));
    }
    
    /**
     * Invalid key provider.
     *
     * @return array
     */
    public function invalidKeyProvider() : array
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
     * @expectedException TypeError
     */
    public function testSetWithInvalidKey($key)
    {
        $this->cache->set($key, [0, 1, 2, 3, 4]);
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        $this->assertTrue($this->cache->set('foo', [0, 1, 2, 3, 4]));
        
        usleep(2000100);
        
        $this->assertTrue($this->cache->has('foo'));
    }

    /**
     * Test set with ttl null.
     */
    public function testSetWithTtlAtZero()
    {
        $this->assertTrue($this->cache->set('foo_ttl', [0, 1, 2, 3, 4], 0));
        
        usleep(1000100);
        
        $this->assertTrue($this->cache->has('foo_ttl'));
    }

    /**
     * Test set with ttl value.
     */
    public function testSetWithTtl()
    {
        $this->assertTrue($this->cache->set('foo_ttl', [0, 1, 2, 3, 4], 1));
        
        usleep(1000100);
        
        $this->assertNull($this->cache->get('foo_ttl'));
    }

    /**
     * Test get with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     */
    public function testGetWithInvalidKey($key)
    {
        $this->cache->get($key);
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this->assertTrue($this->cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertEquals([0, 1, 2, 3, 4], $this->cache->get('foo'));
    }

    /**
     * Test get with default value.
     */
    public function testGetWithDefault()
    {
        $this->assertNull($this->cache->get('foo_not_exist'));
    }

    /**
     * Test get with expired element.
     */
    public function testGetWithExpiredElement()
    {
        $this->assertFalse($this->cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertNull($this->cache->get('foo'));
    }

    /**
     * Test delete with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     */
    public function testDeleteWithInvalidKey($key)
    {
        $this->cache->delete($key);
    }

    /**
     * Test delete an existing element.
     */
    public function testDeleteExistingElement()
    {
        $this->assertTrue($this->cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertTrue($this->cache->delete('foo'));
    }

    /**
     * Test delete not existing element.
     */
    public function testDeleteNotExistingElement()
    {
        $this->assertFalse($this->cache->delete('foo'));
    }

    /**
     * Test clear all cache.
     */
    public function testClear()
    {
        $this->assertTrue($this->cache->set('foo_0', [0]));
        $this->assertTrue($this->cache->set('foo_1', [1]));
        $this->assertTrue($this->cache->set('foo_2', [2]));
        $this->assertTrue($this->cache->set('foo_3', [3]));
        $this->assertTrue($this->cache->set('foo_4', [4]));
        $this->assertTrue($this->cache->set('foo_5', [5]));

        $this->assertTrue($this->cache->has('foo_0'));
        $this->assertTrue($this->cache->has('foo_1'));
        $this->assertTrue($this->cache->has('foo_2'));
        $this->assertTrue($this->cache->has('foo_3'));
        $this->assertTrue($this->cache->has('foo_4'));
        $this->assertTrue($this->cache->has('foo_5'));

        $this->assertTrue($this->cache->clear());

        $this->assertFalse($this->cache->has('foo_0'));
        $this->assertFalse($this->cache->has('foo_1'));
        $this->assertFalse($this->cache->has('foo_2'));
        $this->assertFalse($this->cache->has('foo_3'));
        $this->assertFalse($this->cache->has('foo_4'));
        $this->assertFalse($this->cache->has('foo_5'));
    }

    /**
     * Test get multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     */
    public function testGetMultipleWithInvalidKey($key)
    {
        $this->cache->getMultiple($key);
    }

    /**
     * Test get multiple elements.
     */
    public function testGetMultiple()
    {
        $this->assertTrue($this->cache->set('foo_0', [0]));
        $this->assertTrue($this->cache->set('foo_1', [1]));
        $this->assertTrue($this->cache->set('foo_2', [2]));
        $this->assertTrue($this->cache->set('foo_3', [3]));
        $this->assertTrue($this->cache->set('foo_4', [4]));
        $this->assertTrue($this->cache->set('foo_5', [5]));

        $this->assertTrue($this->cache->has('foo_0'));
        $this->assertTrue($this->cache->has('foo_1'));
        $this->assertTrue($this->cache->has('foo_2'));
        $this->assertTrue($this->cache->has('foo_3'));
        $this->assertTrue($this->cache->has('foo_4'));
        $this->assertTrue($this->cache->has('foo_5'));

        $this->assertEquals([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], $this->cache->getMultiple([
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
     * @expectedException TypeError
     */
    public function testSetMultipleWithInvalidKey($key)
    {
        $this->cache->setMultiple($key);
    }

    /**
     * Test set multiple elements.
     */
    public function testSetMultiple()
    {
        $this->assertTrue($this->cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]));

        $this->assertTrue($this->cache->has('foo_0'));
        $this->assertTrue($this->cache->has('foo_1'));
        $this->assertTrue($this->cache->has('foo_2'));
        $this->assertTrue($this->cache->has('foo_3'));
        $this->assertTrue($this->cache->has('foo_4'));
        $this->assertTrue($this->cache->has('foo_5'));
        
        $this->assertEquals([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], $this->cache->getMultiple([
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
     */
    public function testSetMultipleTtl()
    {
        $this->assertTrue($this->cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], 1));

        $this->assertTrue($this->cache->has('foo_0'));
        $this->assertTrue($this->cache->has('foo_1'));
        $this->assertTrue($this->cache->has('foo_2'));
        $this->assertTrue($this->cache->has('foo_3'));
        $this->assertTrue($this->cache->has('foo_4'));
        $this->assertTrue($this->cache->has('foo_5'));

        usleep(1000100);
        
        $this->assertNull($this->cache->get('foo_0'));
        $this->assertNull($this->cache->get('foo_1'));
        $this->assertNull($this->cache->get('foo_2'));
        $this->assertNull($this->cache->get('foo_3'));
        $this->assertNull($this->cache->get('foo_4'));
        $this->assertNull($this->cache->get('foo_5'));
    }

    /**
     * Teset delete multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     */
    public function testDeleteMultipleWithInvalidKey($key)
    {
        $this->cache->deleteMultiple($key);
    }

    /**
     * Test delete multiple elements.
     */
    public function testDeleteMultiple()
    {
        $this->assertTrue($this->cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]));

        $this->assertTrue($this->cache->has('foo_0'));
        $this->assertTrue($this->cache->has('foo_1'));
        $this->assertTrue($this->cache->has('foo_2'));
        $this->assertTrue($this->cache->has('foo_3'));
        $this->assertTrue($this->cache->has('foo_4'));
        $this->assertTrue($this->cache->has('foo_5'));

        $this->assertTrue($this->cache->deleteMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));

        $this->assertFalse($this->cache->has('foo_0'));
        $this->assertFalse($this->cache->has('foo_1'));
        $this->assertFalse($this->cache->has('foo_2'));
        $this->assertFalse($this->cache->has('foo_3'));
        $this->assertFalse($this->cache->has('foo_4'));
        $this->assertFalse($this->cache->has('foo_5'));
    }

    /**
     * Test has with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     */
    public function testHasWithInvalidKey($key)
    {
        $this->cache->has($key);
    }

    /**
     * Test has with existing element.
     */
    public function testHasExistingElement()
    {
        $this->assertTrue($this->cache->set('foo', [0, 1, 2, 3, 4]));
        
        $this->assertTrue($this->cache->has('foo'));
    }
    
    /**
     * Test has with not existing element.
     */
    public function testHasNotExistingElement()
    {
        $this->assertFalse($this->cache->has('foo_false'));
    }
    
    /**
     * Test has with expired element.
     */
    public function testHasWithExpiredElement()
    {
        $this->assertFalse($this->cache->set('foo', [0, 1, 2, 3, 4], -10));
        
        $this->assertFalse($this->cache->has('foo'));
    }
}
