<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
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
    public $name = '';

    /**
     * @var string Permission description
     */
    public $description = '';

    /**
     * @var string Last update
     */
    public $lastUpdate = '';

    /**
     * @var int Id of the group from which the permission was inherited
     */
    public $inherited = 0;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        settype($this->rId, 'integer');
        settype($this->objectId, 'integer');
    }
}
