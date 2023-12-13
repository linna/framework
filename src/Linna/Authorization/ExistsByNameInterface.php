<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

/**
 * Exists By Name Interface.
 *
 * <p>Contain methods required from a mapper to check if a specific object exists.</p>
 */
interface ExistsByNameInterface
{
    /**
     * Check if a object exist.
     *
     * @param string $objectName The object will be checked as object name.
     *
     * @return bool True if the object exists, false otherwise.
     */
    public function ExistByName(string $objectName): bool;
}
