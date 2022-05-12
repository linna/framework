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

class ClassConcreteB implements ClassInterface
{
    private $object = 'ClassConcreteB';

    public function __construct()
    {
    }

    public function inheritedMethod()
    {
        return $this->object;
    }
}
