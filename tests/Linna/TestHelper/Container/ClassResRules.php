<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Container;

class ClassResRules
{
    private $classB;
    private $classARules;

    public function __construct(ClassB $b, ClassARules $aRules)
    {
        $this->classB = $b;
        $this->classARules = $aRules;
    }
}
