<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use Linna\Shared\AbstractStorageFactory;
use Psr\SimpleCache\CacheInterface;

/**
 * Cache Factory.
 */
class CacheFactory extends AbstractStorageFactory
{
    /** @var array<string, string> Factory supported driver. */
    protected array $supportedDriver = [
        'disk'       => DiskCache::class,
        'memcached'  => MemcachedCache::class,
    ];

    /**
     * Return a resource or an object to intercat with the cache.
     *
     * @return CacheInterface The cache provider.
     */
    public function get(): CacheInterface
    {
        return $this->returnStorageObject();
    }
}
