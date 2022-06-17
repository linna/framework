<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

use Linna\DataMapper\DomainObjectAbstract;

/**
 * Permission.
 */
class Permission extends DomainObjectAbstract
{
    /**
     * @var string Permission name
     */
    public string $name = '';

    /**
     * @var string Permission description
     */
    public string $description = '';

    /**
     * @var int Id of the group from which the permission was inherited
     */
    public int $inherited = 0;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        //\settype($this->id, 'integer');
        \settype($this->inherited, 'integer');
    }
}
