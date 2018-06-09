<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Cache\DiskCache;
use Linna\Cache\CacheFactory;
use Linna\Cache\MemcachedCache;
use PHPUnit\Framework\TestCase;

/**
 * Cache factory test.
 */
class CacheFactoryTest extends TestCase
{
    /**
     * Test create disk cache resource.
     */
    public function testCreateDiskCache(): void
    {
        $this->assertInstanceOf(DiskCache::class, (new CacheFactory('disk', []))->get());
    }

    /**
     * Test create memcached cache resource
     *
     * @requires extension memcached
     */
    public function testCreateMemcachedCache(): void
    {
        $memcached = new Memcached();
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        $this->assertInstanceOf(MemcachedCache::class, (new CacheFactory('memcached', ['resource' => $memcached]))->get());
    }

    /**
     * Test unsupported cache resource.
     *
     * @expectedException InvalidArgumentException
     */
    public function testUnsupportedCache(): void
    {
        (new CacheFactory('', []))->get();
    }
}
