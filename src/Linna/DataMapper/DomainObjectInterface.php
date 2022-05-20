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

/**
 * Interface for Domain Object.
 */
interface DomainObjectInterface
{
    /**
     * Get the object id/uuid of this object (unique to the object type), 
     * it can be an integer or a uuid as string.
     *
     * @return int|string
     */
    public function getId(): mixed;

    /**
     * Set the object id/uuid for this object, it can be an integer or a uuid as string.
     *
     * @param int|string $objectId
     *
     * @return int|string The new object id/uuid
     */
    public function setId(int|string $objectId): mixed;
}
