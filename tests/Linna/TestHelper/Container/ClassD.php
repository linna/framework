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

class ClassD
{
    private $classE;
    private $classF;
    private $classG;

    public function __construct(ClassE $e, ClassF $f, ClassG $g)
    {
        $this->classE = $e;
        $this->classF = $f;
        $this->classG = $g;
    }
}
