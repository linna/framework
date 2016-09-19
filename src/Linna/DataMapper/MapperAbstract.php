<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\DataMapper;

/**
 * Abstract Class for ObjectMapper
 */
abstract class MapperAbstract
{
    /**
     * Create a new instance of the DomainObject that this
     * mapper is responsible for
     *
     * @return DomainObjectAbstract
     */
    public function create()
    {
        return $this->oCreate();
    }

    /**
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required
     *
     * @param DomainObjectAbstract $domainObject
     */
    public function save(DomainObjectInterface $domainObject)
    {
        if ($domainObject->getId() === 0) {
            return $this->oInsert($domainObject);
        }
        
        return $this->oUpdate($domainObject);
    }

    /**
     * Delete the DomainObject from persistent storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    public function delete(DomainObjectInterface $domainObject)
    {
        return $this->oDelete($domainObject);
    }

   
    /**
     * Create a new instance of a DomainObject
     *
     * @return DomainObjectAbstract
     */
    abstract protected function oCreate();

    /**
     * Insert the DomainObject to persistent storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function oInsert(DomainObjectInterface $domainObject);

    /**
     * Update the DomainObject in persistent storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function oUpdate(DomainObjectInterface $domainObject);

    /**
     * Delete the DomainObject from peristent Storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function oDelete(DomainObjectInterface $domainObject);
}
