<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

use UnexpectedValueException;

/**
 * Abstract Class for Domain Object.
 */
abstract class DomainObjectAbstract implements DomainObjectInterface
{
    use DomainObjectTimeTrait;

    /**
     * @var null|int|string Read only domain object id or domain object uuid on persistent storage.
     *
     * null means that the id or the uuid is not set!
     */
    protected null|int|string $id = null;

    /**
     * Get the id or the uuid of the domain object (unique for each domain object, also domain objects of the same type).
     *
     * @return mixed The curren domain object id or uuid, the type of the value could be only int or string.
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * Set the id ot the uuid for the domain object.
     *
     * @param int|string $id The new domain object id.
     *
     * @throws UnexpectedValueException If the id or the uuid on the domain object is already set.
     *
     * @return mixed New domain object id or uuid, the type of the value could be only int or string.
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
     * Return the value of a private or protected property. Works only for the id or uuid property.
     *
     * @param string $name The name of the property which will be accessed.
     *
     * @return mixed The value of the property.
     */
    public function __get(string $name)
    {
        if ($name === 'id') {
            return $this->id;
        }
    }

    /**
     * Return if a private or protected property exists. Works only for the id or uuid property.
     *
     * @param string $name The name of the property which will be checked.
     *
     * @return bool True if the property exists, false otherwise.
     */
    public function __isset(string $name)
    {
        if ($name === 'id') {
            return true;
        }

        return false;
    }
}
