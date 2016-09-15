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
     * @var int $objectId Object id, same of db record
     */
    protected $objectId = 0;

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
     * @throws UnexpectedValueException If the id on the object is already set
     */
    public function setId($objectId)
    {
        try {
            if ($this->objectId !== 0) {
                throw new \UnexpectedValueException('objectId is immutable');
            }
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
        
        return $this->objectId = (int) $objectId;
    }
}
