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

/**
 * Interface for Domain Object.
 *
 * This interface declare how to get and how to set the id or the uuid of a domain object.
 */
interface DomainObjectInterface
{
    /**
     * Get the id or the uuid of the domain object (unique for each domain object, also domain objects of the same type).
     *
     * @return mixed The curren domain object id or uuid, the type of the value could be only int or string.
     */
    public function getId(): mixed;

    /**
     * Set the id ot the uuid for the domain object.
     *
     * @param int|string $objectId The new domain object id.
     *
     * @throws UnexpectedValueException If the id or the uuid on the domain object is already set.
     *
     * @return mixed New domain object id or uuid, the type of the value could be only int or string.
     */
    public function setId(int|string $objectId): mixed;
}
