<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Database;

/**
 * Abstract Class for ObjectMapper
 */
abstract class MapperAbstract
{
    /**
     * Create a new instance of the DomainObject that this
     * mapper is responsible for.
     *
     * @return DomainObjectAbstract
     *
     * @since 0.1.0
     */
    public function create()
    {
        $obj = $this->_create();
        
        return $obj;
    }

    /**
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required.
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    public function save(DomainObjectInterface ...$obj)
    {
        $obj = $obj[0];
        
        if ($obj->getId() === 0) {
            return $this->_insert($obj);
        }
        
        return $this->_update($obj);
    }

    /**
     * Delete the DomainObject from persistent storage.
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    public function delete(DomainObjectInterface $obj)
    {
        $this->_delete($obj);
    }

   
    /**
     * Create a new instance of a DomainObject
     * 
     * @return DomainObjectAbstract
     *
     * @since 0.1.0
     */
    abstract protected function _create();

    /**
     * Insert the DomainObject to persistent storage 
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    abstract protected function _insert(DomainObjectInterface $obj);

    /**
     * Update the DomainObject in persistent storage 
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    abstract protected function _update(DomainObjectInterface $obj);

    /**
     * Delete the DomainObject from peristent Storage
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    abstract protected function _delete(DomainObjectInterface $obj);
}
