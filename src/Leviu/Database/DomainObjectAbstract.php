<?php

/**
 * Leviu.
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
namespace Leviu\Database;

/**
 * Abstract Class for Domain Object
 */
abstract class DomainObjectAbstract
{
    /**
     *
     * @var int $_id Id of the Object, same of db record.
     */
    protected $_id = null;

    /**
     * Get the ID of this object (unique to the
     * object type).
     *
     * @return int
     *
     * @since 0.1.0
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set the id for this object.
     *
     * @param int $id
     *
     * @return int
     *
     * @throws Exception If the id on the object is already set
     *
     * @since 0.1.0
     */
    public function setId($id)
    {
        if (!is_null($this->_id)) {
            throw new Exception('ID is immutable');
        }

        return $this->_id = (int) $id;
    }
}
