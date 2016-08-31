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
 * Abstract Class for Domain Object
 */
abstract class DomainObjectAbstract implements DomainObjectInterface
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
     */
    public function setId($id)
    {
        if (!is_null($this->_id)) {
            throw new Exception('ID is immutable');
        }

        return $this->_id = (int) $id;
    }
}
