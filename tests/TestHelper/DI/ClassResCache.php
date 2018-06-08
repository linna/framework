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

class ClassResCache
{
    private $classB;
    private $classACache;

    public function __construct(ClassB $b, ClassACache $aCache)
    {
        $this->classB = $b;
        $this->classACache = $aCache;
    }
}
