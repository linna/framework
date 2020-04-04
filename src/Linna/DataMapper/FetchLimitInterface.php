<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

/**
 * Fetch Limit Interface
 * Contain an optional method for basic Mapper.
 */
interface FetchLimitInterface
{
    /**
     * Fetch DomainObject with limit.
     * This method must return an array containing
     * a DomainObject object filtered with sql limit style
     * or a void array.
     *
     * @param int $offset   Offset of the first row to return
     * @param int $rowCount Maximum number of rows to return
     *
     * @return array<mixed>
     */
    public function fetchLimit(int $offset, int $rowCount): array;
}
