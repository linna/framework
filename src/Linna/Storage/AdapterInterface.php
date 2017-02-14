<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage;

/**
 * Adapter Interface.
 */
interface AdapterInterface
{
    /**
     * Return resource to Database class.
     */
    public function getResource();
}
