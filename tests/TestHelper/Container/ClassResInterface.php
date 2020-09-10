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

class ClassResInterface
{
    private $class;

    public function __construct(ClassInterface $object)
    {
        $this->class = $object;
    }

    public function getClass()
    {
        return $this->class->inheritedMethod();
    }
}
