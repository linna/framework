<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2023, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use DateInterval;
use DateTime;

/**
 * Provide methods to handle cache TTL.
 *
 */
trait TtlTrait
{
    /**
     * Handle TTL parameter.
     *
     * <p>the actual value sent may either be Unix time (number of seconds since January 1, 1970, as an integer), or
     * a number of seconds starting from current time. In the latter case, this number of seconds may not exceed
     * 60*60*24*30 (number of seconds in 30 days); if the expiration value is larger than that, the server will
     * consider it to be real Unix time value rather than an offset from current time. </p>
     *
     * @param DateInterval|int|null $ttl Optional. The TTL value of this item. If no value is sent and
     *                                   the driver supports TTL then the library may set a default value
     *                                   for it or let the driver take care of that.
     *
     * @return int TTL in seconds, if the value is 0 then consider that the cache does not expire, if the value is
     *             negative then consider the to delete the cached value.
     */
    public function handleTtl(DateInterval|int|null $ttl): int
    {
        //not expire
        if ($ttl === null) {
            return 0;
        }

        //date interval
        if ($ttl instanceof DateInterval) {
            $now = new DateTime();
            $now->add($ttl);
            return (int) $now->format('U') - \time();
        }

        //timestamp if future
        if (($validTimestamp = $this->isValidUnixTimestamp($ttl)) !== false) {
            return $ttl - \time();
        }

        //seconds
        if (\is_int($ttl) && !$validTimestamp) {
            return $ttl;
        }
    }

    /**
     * Check if the integer provided is a valid future unix timestamp.
     *
     * @param int $timestamp Timestamp to check.
     *
     * @return bool
     */
    private function isValidUnixTimestamp(int $timestamp): bool
    {
        if ($timestamp > 86400 * 30  && $timestamp < PHP_INT_MAX) {
            return true;
        }

        return false;
    }
}
