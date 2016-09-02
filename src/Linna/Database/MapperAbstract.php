<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Database;

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
        return $this->_create();
    }

    /**
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required
     *
     * @param DomainObjectAbstract $domainObject
     */
    public function save(DomainObjectInterface ...$domainObject)
    {
        $domainObject = $domainObject[0];
        
        if ($domainObject->getId() === 0) {
            return $this->_insert($domainObject);
        }
        
        return $this->_update($domainObject);
    }

    /**
     * Delete the DomainObject from persistent storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    public function delete(DomainObjectInterface $domainObject)
    {
        $this->_delete($domainObject);
    }

   
    /**
     * Create a new instance of a DomainObject
     *
     * @return DomainObjectAbstract
     */
    abstract protected function _create();

    /**
     * Insert the DomainObject to persistent storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function _insert(DomainObjectInterface $domainObject);

    /**
     * Update the DomainObject in persistent storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function _update(DomainObjectInterface $domainObject);

    /**
     * Delete the DomainObject from peristent Storage
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function _delete(DomainObjectInterface $domainObject);
}
