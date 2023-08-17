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

use Linna\DataMapper\DomainObjectAbstract;

/**
 * Permission domain object.
 */
class Permission extends DomainObjectAbstract
{
    /**
     * Class Constructor.
     *
     * @param string  $name        The permission name.
     * @param string  $description The permission description.
     * @param integer $inherited   Specify if the permission is inherited from a group.
     */
    public function __construct(
        /** @var string Permission name. */
        public string $name = '',

        /** @var string Permission description. */
        public string $description = '',

        /** @var int Id of the group from which the permission was inherited. */
        public int $inherited = 0
    ) {
    }
}
