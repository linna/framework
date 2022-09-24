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
 * Null Domain Object.
 *
 * Use this to represent a non existing object.
 */
class NullDomainObject extends DomainObjectAbstract
{
    /**
     * Get nothing.
     *
     * This method overrides the original method inherited from the abstract
     * class and make it idempotent.
     *
     * @return mixed Allways null.
     */
    public function getId(): mixed
    {
        return null;
    }

    /**
     * Set nothing.
     *
     * This method overrides the original method inherited from the abstract
     * class and make it idempotent.
     *
     * @param int|string $id The new domain object id, in this method the id or the uuid will be unset.
     *
     * @return mixed Allways null.
     */
    public function setId(int|string $id): mixed
    {
        //make this method idempotent
        unset($id);

        return null;
    }
}
