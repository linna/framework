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
    public $name;

    /**
     * @var string Permission description
     */
    public $description;

    /**
     * @var string Last update
     */
    public $lastUpdate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        settype($this->objectId, 'integer');
    }
}
