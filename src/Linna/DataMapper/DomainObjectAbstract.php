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

use Linna\DataMapper\DomainObjectInterface;
use \UnexpectedValueException;

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
    public function getId(): int
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
    public function setId(int $objectId): int
    {
        try {
            if ($this->objectId !== 0) {
                throw new UnexpectedValueException('objectId is immutable');
            }
        } catch (UnexpectedValueException $e) {
            echo 'UnexpectedValueException: ',$e->getMessage();
        }
        
        return $this->objectId = (int) $objectId;
    }
}
