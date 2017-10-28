<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\DI;

class ClassARules
{
    public function __construct(bool $aaBool, ClassI $i, string $aaString, int $aaInt, array $aaArray, $aaNoType)
    {
        return $aaBool;
    }
}
