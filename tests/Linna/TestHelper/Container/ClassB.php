<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\TestHelper\Container;

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
