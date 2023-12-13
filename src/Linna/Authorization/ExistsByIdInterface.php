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
 * Exists By Id Interface.
 *
 * <p>Contain methods required from a mapper to check if a specific object exists.</p>
 */
interface ExistsByIdInterface
{
    /**
     * Check if the object exist.
     *
     * @param int|string $objectId The object will be checked as object id.
     *
     * @return bool True if the object exists, false otherwise.
     */
    public function ExistById(int|string $objectId): bool;
}
