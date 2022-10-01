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
 * Fetch By Name Interface.
 *
 * <p>Contain an optional method for basic Mapper.</p>
 */
interface FetchByNameInterface
{
    /**
     * Fetch a domain object from persistent storage by name.
     *
     * <p>This method must return an instance of the requested domain object or an
     * instance of a null domain object.</p>
     *
     * @param string $objectName The name of the object which will be searched.
     *
     * @return DomainObjectInterface The domain object if exists, the null domain object otherwise.
     */
    public function fetchByName(string $objectName): DomainObjectInterface;
}
