<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

/**
 * Fetch Limit Interface.
 *
 * Contain an optional method for basic Mapper.
 */
interface FetchLimitInterface
{
    /**
     * Fetch a list of domain objects stored in persistent storage for a
     * specific domain using a limit clausole.
     *
     * This method must return an array containing all instances, in interval
     * specified by the limit, of domain objects for a specific domain or a
     * void array.
     *
     * @param int $offset   Offset of the first row to return.
     * @param int $rowCount Maximum number of rows to return.
     *
     * @return array<mixed> The array with domain objects or void.
     */
    public function fetchLimit(int $offset, int $rowCount): array;
}
