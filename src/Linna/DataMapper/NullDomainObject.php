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

use Linna\DataMapper\Exception\NullDomainObjectException;

/**
 * Null Domain Object.
 *
 * <p>Use this to represent a non existing domain object.</p>
 */
class NullDomainObject extends DomainObjectAbstract
{
    /**
     * Get nothing.
     *
     * <p>This method overrides the original method inherited from the abstract
     * class and make it idempotent.</p>
     *
     * @return int|string Allways null.
     *
     * @throws NullDomainObjectException If this method is used.
     */
    public function getId(): int|string
    {
        throw new NullDomainObjectException('NullDomainObject doesn\'t have an id.');
    }

    /**
     * Set nothing.
     *
     * <p>This method overrides the original method inherited from the abstract
     * class and make it idempotent.</p>
     *
     * @param int|string $id The new domain object id, in this method the id or the uuid will be unset.
     *
     * @return int|string Allways null.
     *
     * @throws NullDomainObjectException If this method is used.
     */
    public function setId(int|string $id): int|string
    {
        //make this method idempotent
        unset($id);

        throw new NullDomainObjectException('Not possible to set the id for NullDomainObject.');
    }
}
