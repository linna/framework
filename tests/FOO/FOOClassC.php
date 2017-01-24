<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\FOO;

class FOOClassC
{
    public function __construct(FOOClassG $g)
    {
        return 'C';
    }
}
