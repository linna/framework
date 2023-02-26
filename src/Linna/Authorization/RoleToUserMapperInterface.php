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
 * Role To User Mapper Interface.
 *
 * <p>Contain methods required for concrete <code>Role</code> and <code>EnhancedUser</code> mappers to avoid
 * recursion when fetch objects.</p>
 *
 * <p>Using <code>Role</code> and <code>EnhancedUser</code> mappers without this third mapper will be impossible
 * because a <code>Role</code> mapper require an <code>EnhancedUser</code> mapper to create an instance
 * and vice versa.</p>
 */
interface RoleToUserMapperInterface extends FetchByRoleInterface, FetchByUserInterface
{
}
