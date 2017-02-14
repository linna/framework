<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\FOO;

class FOOClassResObject
{
    public function __construct(FOOClassB $b)
    {
        return 'ResObject';
    }
}
