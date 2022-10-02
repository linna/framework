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
     * @return mixed Allways null.
     * 
     * @todo Have to throw an exception if this method are called
     */
    public function getId(): mixed
    {
        // have to throw an exception if this method are called
        return null;
    }

    /**
     * Set nothing.
     *
     * <p>This method overrides the original method inherited from the abstract
     * class and make it idempotent.</p>
     *
     * @param int|string $id The new domain object id, in this method the id or the uuid will be unset.
     *
     * @return mixed Allways null.
     * 
     * @todo Have to throw an exception if this method are called
     */
    public function setId(int|string $id): mixed
    {
        //make this method idempotent
        unset($id);

        return null;
    }
}
