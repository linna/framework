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
 * Fetch By Name Interface
 * Contain an optional method for basic Mapper.
 */
interface FetchByNameInterface
{
    /**
     * Fetch a DomainObject by name.
     * From object name as argument, this method must return an instance
     * of DomainObject instance or an instance of NullDomainObject.
     *
     * @param string $objectName
     *
     * @return DomainObjectInterface
     */
    public function fetchByName(string $objectName): DomainObjectInterface;
}
