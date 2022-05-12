<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

/**
 * Mapper Interface uuid version.
 * Contain methods required from basic Mapper.
 */
interface UuidMapperInterface
{
    /**
     * Fetch a DomainObject by id.
     * From object uuid as argument, this method must return an instance
     * of DomainObject instance or an instance of NullDomainObject.
     *
     * @param string $objectId
     *
     * @return UuidDomainObjectInterface
     */
    public function fetchById(string $objectId): UuidDomainObjectInterface;
}
