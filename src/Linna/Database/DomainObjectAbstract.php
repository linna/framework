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
 * Abstract Class for Domain Object
 */
abstract class DomainObjectAbstract implements DomainObjectInterface
{
    /**
     * @var int $_id Id of the Object, same of db record
     */
    protected $_Id = null;

    /**
     * Get the ID of this object (unique to the
     * object type).
     *
     * @return int
     */
    public function getId()
    {
        return $this->_Id;
    }

    /**
     * Set the id for this object
     *
     * @param int $objectId
     *
     * @return int
     *
     * @throws Exception If the id on the object is already set
     */
    public function setId($objectId)
    {
        if (!is_null($this->_Id)) {
            throw new Exception('ID is immutable');
        }

        return $this->_Id = (int) $objectId;
    }
}
