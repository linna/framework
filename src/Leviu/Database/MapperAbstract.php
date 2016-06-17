<?php

/**
 * Leviu.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace Leviu\Database;

/**
 * MapperAbstract
 * - Abstract Class for ObjectMapper.
 */
abstract class MapperAbstract
{
    /**
     * create.
     * 
     * Create a new instance of the DomainObject that this
     * mapper is responsible for. Optionally populating it
     * from a data array.
     *
     * @param array $data
     *
     * @return DomainObjectAbstract
     *
     * @since 0.1.0
     */
    public function create()//$data = null)
    {
        $obj = $this->_create();
        //if ($data) {
        //    $obj = $this->populate($obj, $data);
        //}
        return $obj;
    }

    /**
     * save.
     * 
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required.
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    public function save(DomainObjectAbstract $obj)
    {
        if (is_null($obj->getId())) {
            $this->_insert($obj);
            //$obj->setId($id);
        } else {
            $this->_update($obj);
        }
    }

    /**
     * delete.
     * 
     * Delete the DomainObject from persistent storage.
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    public function delete(DomainObjectAbstract $obj)
    {
        $this->_delete($obj);
    }

    /**
     * populate.
     * 
     * Populate the DomainObject with the values
     * from the data array.
     * 
     * To be implemented by the concrete mapper class
     *
     * @param DomainObjectAbstract $obj
     * @param array                $data
     *
     * @return DomainObjectAbstract
     *
     * @since 0.1.0
     * @deprecated since version 0.1.0 Replaced with \PDO::FETCH_CLASS fetch option
     */
    abstract public function populate(DomainObjectAbstract $obj, $data);

    /**
     * _create.
     * 
     * Create a new instance of a DomainObject
     * 
     * @return DomainObjectAbstract
     *
     * @since 0.1.0
     */
    abstract protected function _create();

    /**
     * _insert.
     * 
     * Insert the DomainObject to persistent storage 
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    abstract protected function _insert(DomainObjectAbstract $obj);

    /**
     * _update.
     * 
     * Update the DomainObject in persistent storage 
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    abstract protected function _update(DomainObjectAbstract $obj);

    /**
     * _delete.
     * 
     * Delete the DomainObject from peristent Storage
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    abstract protected function _delete(DomainObjectAbstract $obj);
}
