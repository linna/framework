<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

/**
 * Mapper Interface
 * Contain methods required from basic Mapper.
 */
interface MapperInterface
{
    /**
     * Fetch a DomainObject by id.
     * From object id as argument, this method must return an instance
     * of DomainObject instance or an instance of NullDomainObject.
     * 
     * @param string $objectId
     *
     * @return DomainObjectInterface
     */
    public function fetchById(int $objectId) : DomainObjectInterface;

    /**
     * Fetch all DomainObject stored in data base.
     * This method must return an array containing
     * a DomainObject object instance for every concrete domain object
     * or a void array.
     * 
     * @return array
     */
    public function fetchAll() : array;

    /**
     * Fetch DomainObject with limit.
     * This method must return an array containing
     * a DomainObject object filtered with sql limit style
     * or a void array.
     * 
     * @param int $offset Offset of the first row to return
     * @param int $rowCount Maximum number of rows to return
     *
     * @return array
     */
    public function fetchLimit(int $offset, int $rowCount) : array;
}
