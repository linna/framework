<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

/**
 * Role To User Mapper Interface.
 *
 * Contain methods required for concrete Role And EnhancedUser Mappers to avoid
 * recursion when fetch objects.
 *
 * Using Role and EnhancedUser mappers without this third mapper was impossible
 * because Role Mapper will require EnhancedUser Mapper to create an instance
 * and vice versa.
 */
interface RoleToUserMapperInterface extends FetchByRoleInterface, FetchByUserInterface
{
}
