<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Database;

/**
 * Interface for Domain Object
 */
interface DomainObjectInterface
{
    public function getId();
    
    public function setId($id);
}
