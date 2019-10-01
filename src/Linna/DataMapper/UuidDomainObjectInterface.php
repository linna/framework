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
 * Interface for Domain Object UUID version.
 */
interface UuidDomainObjectInterface
{
    /**
     * Get the UUID of this object (unique to the object type).
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Set the uuid for this object.
     *
     * @param string $objectId
     *
     * @return string
     */
    public function setId(string $objectId): string;
}
