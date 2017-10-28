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

class ClassD
{
    public function __construct(ClassE $e, ClassF $f, ClassG $g)
    {
        return 'D';
    }
}
