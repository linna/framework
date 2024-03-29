<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

use UnexpectedValueException;

/**
 * Interface for Domain Object.
 *
 * <p>This interface declare how to get and how to set the id or the uuid of a domain object.</p>
 */
interface DomainObjectInterface
{
    /**
     * Get the id or the uuid of the domain object (unique for each domain object, also domain objects of the same
     * type).
     *
     * @return null|int|string The curren domain object id or uuid, the type of the value could be only int or string.
     */
    public function getId(): null|int|string;

    /**
     * Set the id ot the uuid for the domain object.
     *
     * @param int|string $objectId The new domain object id.
     *
     * @throws UnexpectedValueException If the id or the uuid on the domain object is already set.
     *
     * @return int|string New domain object id or uuid, the type of the value could be only int or string.
     */
    public function setId(int|string $objectId): int|string;

    /**
     * Check if the domain object has a valid id.
     *
     * <p>Opposite of <code>hasNotId()</code> method</p>
     *
     * @return bool True if the object has the id set, false otherwise.
     */
    public function hasId(): bool;

    /**
     * Check if the domain object has a null id.
     *
     * <p>Opposite of <code>hasId()</code> method</p>
     *
     * @return bool True if the object has the id set, false otherwise.
     */
    public function hasNotId(): bool;
}
