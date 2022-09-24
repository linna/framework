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
 * Fetch All Interface.
 *
 * Contains an optional method for basic Mapper.
 */
interface FetchAllInterface
{
    /**
     * Fetch all domain objects stored in persistent storage for a specific domain.
     *
     * This method must return an array containing all instances of domain
     * objects for a specific domain or a void array.
     *
     * @return array<mixed> The array with domain objects or void.
     */
    public function fetchAll(): array;
}
