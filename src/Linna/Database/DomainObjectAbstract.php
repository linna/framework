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
     * @var int $_Id Object id, same of db record
     */
    protected $objectId = null;

    /**
     * Get the ID of this object (unique to the
     * object type).
     *
     * @return int
     */
    public function getId()
    {
        return $this->objectId;
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
        if (!is_null($this->objectId)) {
            throw new Exception('ID is immutable');
        }

        return $this->objectId = (int) $objectId;
    }
}
