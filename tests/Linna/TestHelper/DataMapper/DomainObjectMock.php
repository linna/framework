<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\TestHelper\DataMapper;

use Linna\DataMapper\DomainObjectAbstract;

class DomainObjectMock extends DomainObjectAbstract
{
    public string $name = '';

    public string $propertyOne = '';

    public string $propertyTwo = '';

    public string $propertyThree = '';
}
