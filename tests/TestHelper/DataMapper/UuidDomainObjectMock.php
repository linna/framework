<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\DataMapper;

use Linna\DataMapper\UuidDomainObjectAbstract;

class UuidDomainObjectMock extends UuidDomainObjectAbstract
{
    public string $propertyOne = '';

    public string $propertyTwo = '';

    public string $propertyThree = '';

    public function __construct()
    {
    }
}
