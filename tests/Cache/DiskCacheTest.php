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
            [[0,1]], 
            [(object)[0, 1]], 
            [1.5],
        ];
    }
    
    /**
     * @dataProvider KeyProvider
     */
    public function testSetInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->cache->set($key, [0,1,2,3,4]);
    }
    
    public function testSet()
    {
        $this->cache->set('foo', [0,1,2,3,4]);
        
        $this->assertEquals(true, file_exists('/tmp/'. sha1('foo') . '.php'));
    }
    
    public function testSetSerialize()
    {
        $this->cacheSerialize->set('foo_serialize', [0,1,2,3,4]);
        
        $this->assertEquals(true, file_exists('/tmp/'. sha1('foo_serialize') . '.php'));
    }
    
    public function testSetTtlNull()
    {
        $this->cache->set('foo_ttl', [0,1,2,3,4]);
        
        $cacheValue = include '/tmp/'. sha1('foo_ttl') . '.php';
        
        $this->assertEquals(null, $cacheValue['expires']);
    }
    
    public function testSetTtl()
    {
        $this->cache->set('foo_ttl', [0,1,2,3,4], 10);
        
        $cacheValue = include '/tmp/'. sha1('foo_ttl') . '.php';
        
        $this->assertEquals(true, ($cacheValue['expires'] > time()));
    }
    
    public function testSetTtlDateInterval()
    {
        $this->cache->set('foo_ttl', [0,1,2,3,4], new DateInterval('PT10S'));
        
        $cacheValue = include '/tmp/'. sha1('foo_ttl') . '.php';
        
        $this->assertEquals(true, ($cacheValue['expires'] > time()));
    }
    
    /**
     * @dataProvider KeyProvider
     */
    public function testGetInvalidKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->cache->get($key, [0,1,2,3,4]);
    }
    
    public function testGet()
    {
        $this->cache->set('foo', [0,1,2,3,4]);
        
        $this->assertEquals(true, file_exists('/tmp/'. sha1('foo') . '.php'));
        
        $this->assertEquals([0,1,2,3,4], $this->cache->get('foo'));
    }
    
    public function testGetSerialize()
    {
        $this->cacheSerialize->set('foo_serialize', [0,1,2,3,4]);
        
        $this->assertEquals(true, file_exists('/tmp/'. sha1('foo_serialize') . '.php'));
        
        $this->assertEquals([0,1,2,3,4], $this->cacheSerialize->get('foo_serialize'));
    }
    
    public function testGetDefault()
    {
        $this->assertEquals(null, $this->cache->get('foo_not_exist'));
    }
    
    public function testGetExpired()
    {
        $this->cache->set('foo', [0,1,2,3,4], -10);
        
        $this->assertEquals(null, $this->cache->get('foo'));
    }
}