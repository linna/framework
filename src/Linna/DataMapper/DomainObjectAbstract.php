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
     * @var int Read only object id on persistent storage.
     *
     * -1 means that the id is not set!
     */
    protected int $id = -1;

    /**
     * @var string Read only insertion date on persistent storage.
     */
    public string $created = '';

    /**
     * @var string Read only last update date on persistento storage.
     */
    public string $lastUpdate = '';

    /**
     * Get the id of the object (unique to the object type).
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->getId();
     * </code></pre>
     *
     * @return int Current object id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the id for the object.
     *
     * <pre><code class="php">$object = new DomainObject($dependencies);
     *
     * $object->setId(5);
     * </code></pre>
     *
     * @param int $id New object id.
     *
     * @throws UnexpectedValueException If the id on the object is already set.
     *
     * @return int New object id.
     */
    public function setId(int $id): int
    {
        if ($this->id !== -1) {
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
