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
 * Mapper Interface.
 *
 * Contains methods required from a basic Mapper.
 */
interface MapperInterface
{
    /**
     * Fetch a domain object by id or uuid.
     *
     * This method must return an instance of the requested domain object or an
     * instance of a null domain object.
     *
     * @param int|string $objectId The id or the uuid of the object which will be searched.
     *
     * @return DomainObjectInterface The domain object if exists, the null domain object otherwise.
     */
    public function fetchById(int|string $objectId): DomainObjectInterface;
}
