<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\DataMapper;

/**
 * Interface for Domain Object
 */
interface DomainObjectInterface
{
    /**
     * Get the ID of this object (unique to the
     * object type).
     */
    public function getId();
    
    /**
     * Set the id for this object
     *
     * @param type $objectId
     */
    public function setId(int $objectId);
}
