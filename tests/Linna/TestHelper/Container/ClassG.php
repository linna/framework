<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Container;

class ClassG
{
    private $classI;
    private $classH;

    public function __construct(ClassI $i, ClassH $h)
    {
        $this->classI = $i;
        $this->classH = $h;
    }
}
