<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Cache\MemcachedCache;
use PHPUnit\Framework\TestCase;

class MemcachedCacheTest extends TestCase
{
    /**
     * @var DiskCache Disk Cache resource
     */
    private $cache = null;

    /**
     * Setup.
     */
    public function setUp()
    {
        if (!extension_loaded('memcached')) {
            $this->markTestSkipped(
              'The Memcached extension is not available.'
            );
        }

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
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true,  $this->cache->has('foo'));
    }

    /**
     * Test set with ttl null.
     */
    public function testSetWithTtlNull()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4]);

        $this->assertEquals(true,  $this->cache->has('foo_ttl'));
    }

    /**
     * Test set with ttl value.
     */
    public function testSetWithTtl()
    {
        $this->cache->set('foo_ttl', [0, 1, 2, 3, 4], 1);
        
        usleep(1000005);
        
        $this->assertEquals(null,  $this->cache->get('foo_ttl'));
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
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals([0, 1, 2, 3, 4], $this->cache->get('foo'));
    }

    /**
     * Test get with default value.
     */
    public function testGetWithDefault()
    {
        $this->assertEquals(null, $this->cache->get('foo_not_exist'));
    }

    /**
     * Test get with expired element.
     */
    public function testGetWithExpiredElement()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4], -10);

        $this->assertEquals(null, $this->cache->get('foo'));
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
        $this->cache->set('foo', [0, 1, 2, 3, 4]);

        $this->assertEquals(true, $this->cache->delete('foo'));
    }

    /**
     * Test delete not existing element.
     */
    public function testDeleteNotExistingElement()
    {
        $this->assertEquals(false, $this->cache->delete('foo'));
    }

    /**
     * Test clear all cache.
     */
    public function testClear()
    {
        $this->cache->set('foo_0', [0]);
        $this->cache->set('foo_1', [1]);
        $this->cache->set('foo_2', [2]);
        $this->cache->set('foo_3', [3]);
        $this->cache->set('foo_4', [4]);
        $this->cache->set('foo_5', [5]);

        $this->assertEquals(true, $this->cache->has('foo_0'));
        $this->assertEquals(true, $this->cache->has('foo_1'));
        $this->assertEquals(true, $this->cache->has('foo_2'));
        $this->assertEquals(true, $this->cache->has('foo_3'));
        $this->assertEquals(true, $this->cache->has('foo_4'));
        $this->assertEquals(true, $this->cache->has('foo_5'));

        $this->cache->clear();

        $this->assertEquals(false, $this->cache->has('foo_0'));
        $this->assertEquals(false, $this->cache->has('foo_1'));
        $this->assertEquals(false, $this->cache->has('foo_2'));
        $this->assertEquals(false, $this->cache->has('foo_3'));
        $this->assertEquals(false, $this->cache->has('foo_4'));
        $this->assertEquals(false, $this->cache->has('foo_5'));
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
        $this->cache->set('foo_0', [0]);
        $this->cache->set('foo_1', [1]);
        $this->cache->set('foo_2', [2]);
        $this->cache->set('foo_3', [3]);
        $this->cache->set('foo_4', [4]);
        $this->cache->set('foo_5', [5]);

        $this->assertEquals(true, $this->cache->has('foo_0'));
        $this->assertEquals(true, $this->cache->has('foo_1'));
        $this->assertEquals(true, $this->cache->has('foo_2'));
        $this->assertEquals(true, $this->cache->has('foo_3'));
        $this->assertEquals(true, $this->cache->has('foo_4'));
        $this->assertEquals(true, $this->cache->has('foo_5'));

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
        $this->cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]);

        $this->assertEquals(true, $this->cache->has('foo_0'));
        $this->assertEquals(true, $this->cache->has('foo_1'));
        $this->assertEquals(true, $this->cache->has('foo_2'));
        $this->assertEquals(true, $this->cache->has('foo_3'));
        $this->assertEquals(true, $this->cache->has('foo_4'));
        $this->assertEquals(true, $this->cache->has('foo_5'));
        
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
        $this->cache->SetMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], 1);

        $this->assertEquals(true, $this->cache->has('foo_0'));
        $this->assertEquals(true, $this->cache->has('foo_1'));
        $this->assertEquals(true, $this->cache->has('foo_2'));
        $this->assertEquals(true, $this->cache->has('foo_3'));
        $this->assertEquals(true, $this->cache->has('foo_4'));
        $this->assertEquals(true, $this->cache->has('foo_5'));

        usleep(1000005);
        
        $this->assertEquals(null, $this->cache->get('foo_0'));
        $this->assertEquals(null, $this->cache->get('foo_1'));
        $this->assertEquals(null, $this->cache->get('foo_2'));
        $this->assertEquals(null, $this->cache->get('foo_3'));
        $this->assertEquals(null, $this->cache->get('foo_4'));
        $this->assertEquals(null, $this->cache->get('foo_5'));
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
        $this->cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]);

        $this->assertEquals(true, $this->cache->has('foo_0'));
        $this->assertEquals(true, $this->cache->has('foo_1'));
        $this->assertEquals(true, $this->cache->has('foo_2'));
        $this->assertEquals(true, $this->cache->has('foo_3'));
        $this->assertEquals(true, $this->cache->has('foo_4'));
        $this->assertEquals(true, $this->cache->has('foo_5'));

        $this->cache->deleteMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]);

        $this->assertEquals(false, $this->cache->has('foo_0'));
        $this->assertEquals(false, $this->cache->has('foo_1'));
        $this->assertEquals(false, $this->cache->has('foo_2'));
        $this->assertEquals(false, $this->cache->has('foo_3'));
        $this->assertEquals(false, $this->cache->has('foo_4'));
        $this->assertEquals(false, $this->cache->has('foo_5'));
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
        $this->cache->set('foo', [0, 1, 2, 3, 4]);
        
        $this->assertEquals(true, $this->cache->has('foo'));
    }
    
    /**
     * Test has with not existing element.
     */
    public function testHasNotExistingElement()
    {
        $this->assertEquals(false, $this->cache->has('foo_false'));
    }
    
    /**
     * Test has with expired element.
     */
    public function testHasWithExpiredElement()
    {
        $this->cache->set('foo', [0, 1, 2, 3, 4], -10);

        $this->assertEquals(false, $this->cache->has('foo'));
    }
}
