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

use Linna\DataMapper\FetchByNameInterface;
use Linna\DataMapper\MapperInterface;

/**
 * Permission Mapper Interface.
 *
 * <p>Contain methods required from concrete permission mapper.</p>
 */
interface PermissionMapperInterface extends MapperInterface, FetchByNameInterface, FetchByUserInterface, FetchByRoleInterface
{
}
