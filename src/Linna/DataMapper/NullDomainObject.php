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
 * NullDomainObject.
 */
class NullDomainObject extends DomainObjectAbstract
{
    /**
     * Get nothing.
     * Make this method of the abstract class idempotent
     *
     * @return null
     */
    public function getId(): mixed
    {
        return null;
    }

    /**
     * Set nothing.
     * Make this method of the abstract class idempotent
     *
     * @return null.
     */
    public function setId(int|string $id): mixed
    {
        //make this method idempotent
        unset($id);

        return null;
    }
}
