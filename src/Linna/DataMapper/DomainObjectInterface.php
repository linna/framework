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

/**
 * Interface for Domain Object.
 */
interface DomainObjectInterface
{
    /**
     * Get the ID of this object (unique to the object type).
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Set the id for this object.
     *
     * @param int $objectId
     *
     * @return int
     */
    public function setId(int $objectId): int;
}
