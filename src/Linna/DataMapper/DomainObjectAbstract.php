<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
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
    use DomainObjectTimeTrait;

    /**
     * @var null|int|string Read only object id on persistent storage.
     *
     * null means that the id/uuid is not set!
     */
    protected null|int|string $id = null;

    /**
     * Get the id/uuid of the object (unique to the object type).
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->getId();
     * </code></pre>
     *
     * @return int Current object id.
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * Set the id for the object.
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->setId(5);
     * // or
     * $object->setId('10f6bace-22f3-4b42-9dda-659c89a3a3c9');
     * </code></pre>
     *
     * @param int $id New object id.
     *
     * @throws UnexpectedValueException If the id on the object is already set.
     *
     * @return int New object id.
     */
    public function setId(int|string $id): mixed
    {
        if ($this->id !== null) {
            throw new UnexpectedValueException('ObjectId property is immutable.');
        }

        $this->id = $id;

        return $id;
    }

    /**
     * Return the value of a private or protected property.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        if ($name === 'id') {
            return $this->id;
        }
    }

    /**
     * Return if a private or protected property exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name)
    {
        if ($name === 'id') {
            return true;
        }

        return false;
    }
}
