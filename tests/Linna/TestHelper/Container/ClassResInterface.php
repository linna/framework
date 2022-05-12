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

class ClassResInterface
{
    private $implementation;
    private $b;

    public function __construct(ClassInterface $object, ClassB $b)
    {
        $this->implementation = $object;
        $this->b = $b;
    }

    public function getClass()
    {
        return $this->implementation->inheritedMethod();
    }
}
