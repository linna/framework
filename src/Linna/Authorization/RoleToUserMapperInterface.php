<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

/**
 * Role To User Mapper Interface
 * Contain methods required from concrete Role And EnhancedUser Mappers
 * for avoid recursion when fetch objects.
 * Using Role and EnhancedUser mappers without this third mapper was impossible
 * because Role Mapper will require EnhancedUser Mapper and vice versa to create
 * an instance.
 */
interface RoleToUserMapperInterface extends FetchByRoleInterface, FetchByUserInterface
{
}
