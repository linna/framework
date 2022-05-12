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
 * Abstract Class for Domain Object, UUID version.
 */
abstract class UuidDomainObjectAbstract implements UuidDomainObjectInterface
{
    use DomainObjectTimeTrait;

    /**
     * @var string Read only object id on persistent storage.
     */
    protected string $uuid = '';

    /**
     * Get the UUID of the object (unique to the object type).
     * Alias of $this->getId();
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->getUuid();
     * </code></pre>
     *
     * @return string Current object uuid.
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Get the id of the object (unique to the object type).
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->getId();
     * </code></pre>
     *
     * @return string Current object id.
     */
    public function getId(): string
    {
        return $this->uuid;
    }

    /**
     * Set the uuid for the object.
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->setId('10f6bace-22f3-4b42-9dda-659c89a3a3c9');
     * </code></pre>
     *
     * @param string $uuid New object uuid.
     *
     * @throws UnexpectedValueException If the uuid on the object is already set.
     *
     * @return string New object uid.
     */
    public function setId(string $uuid): string
    {
        if ($this->uuid !== '') {
            throw new UnexpectedValueException('ObjectId property is immutable.');
        }

        $this->uuid = $uuid;

        return $uuid;
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
        if ($name === 'uuid' || $name === 'id') {
            return $this->uuid;
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
        if ($name === 'uuid' || $name === 'id') {
            return true;
        }

        return false;
    }
}
