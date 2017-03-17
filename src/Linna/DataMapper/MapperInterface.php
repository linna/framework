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
 * Contain methods required from basic Mapper
 */
interface MapperInterface
{
    /**
     * Fetch a DomainObject by id.
     *
     * @param string $objectId
     *
     * @return DomainObjectInterface
     */
    public function fetchById(int $objectId) : DomainObjectInterface;


    /**
     * Fetch all DomainObject stored in data base.
     *
     * @return array
     */
    public function fetchAll() : array;
    
    /**
     * Fetch DomainObject with limit.
     *
     * @param int $offset
     * @param int $rowCount
     * 
     * @return array
     */
    public function fetchLimit(int $offset, int $rowCount) : array;

}