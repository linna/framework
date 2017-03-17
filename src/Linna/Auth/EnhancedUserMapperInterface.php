<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

use Linna\DataMapper\MapperInterface;

/**
 * User Mapper Interface
 * Contain methods required from concrete User Mapper.
 */
interface EnhancedUserMapperInterface extends MapperInterface
{
    public function grant(EnhancedUser &$user, string $permission);

    public function revoke(EnhancedUser &$user, string $permission);
}
