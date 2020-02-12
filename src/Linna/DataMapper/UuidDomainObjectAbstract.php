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
     * @var int Read only object id on persistent storage.
     */
    protected int $uuid = 0;

    /**
     * @var string Insertion date on persistent storage.
     */
    public string $created = '';

    /**
     * @var string Last update date on persistento storage.
     */
    public string $lastUpdate = '';

    /**
     * Get the UUID of the object (unique to the object type).
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
     * Set the creation date for the object.
     *
     * @return void
     *
     * @throws UnexpectedValueException If the creation date on the object is already set.
     */
    public function setCreated(): void
    {
        $date = \date(DATE_ATOM);

        if ($this->created !== '') {
            throw new UnexpectedValueException('Creation date property is immutable.');
        }

        $this->created = $date;
    }

    /**
     * Set the time for the last object changes.
     *
     * @return void
     */
    public function setLastUpdate(): void
    {
        $date = \date(DATE_ATOM);

        $this->lastUpdate = $date;
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
        if ($name === 'id') {
            return true;
        }

        return false;
    }
}
