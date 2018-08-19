<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

use UnexpectedValueException;

/**
 * Abstract Class for Domain Object.
 */
abstract class DomainObjectAbstract implements DomainObjectInterface
{
    /**
     * @var int Object id, same of db record
     */
    protected $objectId = 0;

    /**
     * Get the ID of the object (unique to the object type).
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->getId();
     * </code></pre>
     *
     * @return int Current object id
     */
    public function getId(): int
    {
        return $this->objectId;
    }

    /**
     * Set the id for the object.
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->setId(5);
     * </code></pre>
     *
     * @param int $objectId New object id
     *
     * @throws UnexpectedValueException If the id on the object is already set
     *
     * @return int New object id
     */
    public function setId(int $objectId): int
    {
        if ($this->objectId !== 0) {
            throw new UnexpectedValueException('ObjectId property is immutable');
        }

        return $this->objectId = $objectId;
    }
}
