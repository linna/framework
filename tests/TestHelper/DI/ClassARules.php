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

class ClassARules
{
    private $bool;
    private $classI;
    private $string;
    private $int;
    private $array;
    private $noType;
            
    public function __construct(bool $aaBool, ClassI $i, string $aaString, int $aaInt, array $aaArray, $aaNoType)
    {
        $this->bool = $aaBool;
        $this->classI = $i;
        $this->string = $aaString;
        $this->int = $aaInt;
        $this->array = $aaArray;
        $this->noType = $aaNoType;
    }
}
