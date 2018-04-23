<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\DI;

class ClassB
{
    private $classC;
    private $classD;

    public function __construct(ClassC $c, ClassD $d)
    {
        $this->classC = $c;
        $this->classD = $d;
    }
}
