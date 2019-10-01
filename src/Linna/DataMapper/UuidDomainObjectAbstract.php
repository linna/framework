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
 * Abstract Class for Domain Object, UUID version.
 */
abstract class UuidDomainObjectAbstract implements UuidDomainObjectInterface
{
    /**
     * @var string Read only objectId
     */
    public $rId = '';

    /**
     * @var int Object uuid, same of db record
     */
    protected $objectId = '';

    /**
     * Get the UUID of the object (unique to the object type).
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->getId();
     * </code></pre>
     *
     * @return string Current object uuid.
     */
    public function getId(): string
    {
        return $this->objectId;
    }

    /**
     * Set the uuid for the object.
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->setId('10f6bace-22f3-4b42-9dda-659c89a3a3c9');
     * </code></pre>
     *
     * @param string $objectId New object uuid.
     *
     * @throws UnexpectedValueException If the uuid on the object is already set.
     *
     * @return string New object uid.
     */
    public function setId(string $objectId): string
    {
        if ($this->objectId !== '') {
            throw new UnexpectedValueException('ObjectId property is immutable.');
        }

        $this->objectId = $objectId;
        $this->rId = $objectId;

        return $objectId;
    }
}
