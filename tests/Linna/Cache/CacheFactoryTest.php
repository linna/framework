<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

use InvalidArgumentException;
//use Linna\Cache\DiskCache;
//use Linna\Cache\CacheFactory;
//use Linna\Cache\MemcachedCache;
use Memcached;
use PHPUnit\Framework\TestCase;

/**
 * Cache factory test.
 */
class CacheFactoryTest extends TestCase
{
    /**
     * Test create disk cache resource.
     *
     * @return void
     */
    public function testCreateDiskCache(): void
    {
        $this->assertInstanceOf(DiskCache::class, (new CacheFactory('disk', []))->get());
    }

    /**
     * Test create memcached cache resource
     *
     * @requires extension memcached
     *
     * @return void
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
     * @return void
     */
    public function testUnsupportedCache(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new CacheFactory('', []))->get();
    }
}
