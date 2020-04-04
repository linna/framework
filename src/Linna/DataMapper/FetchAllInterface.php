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
 * FetchAllInterface
 * Contain an optional method for basic Mapper.
 */
interface FetchAllInterface
{
    /**
     * Fetch all DomainObject stored in data base.
     * This method must return an array containing
     * a DomainObject object instance for every concrete domain object
     * or a void array.
     *
     * @return array<mixed>
     */
    public function fetchAll(): array;
}
