<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use DateTimeImmutable;
use Linna\Authentication\User;
use Linna\DataMapper\DomainObjectAbstract;

/**
 * Role domain object.
 */
class Role extends DomainObjectAbstract
{
    /**
     * Constructor.
     *
     * @param null|int|string        $id          Permission id.
     * @param string                 $name        The name of the role.
     * @param string                 $description The description of the role.
     * @param integer                $active      Specify is the role is atctive.
     * @param DateTimeImmutable|null $created     Creation datetime.
     * @param DateTimeImmutable|null $lastUpdate  Last updated datetime.
     */
    public function __construct(
        //role id
        null|int|string $id = null,

        /** @var string Group name. */
        public string $name = '',

        /** @var string Group description. */
        public string $description = '',

        /** @var int It say if group is active or not. */
        public int $active = 0,

        //creation datetime
        ?DateTimeImmutable $created = new DateTimeImmutable(),

        //last updated datetime
        ?DateTimeImmutable $lastUpdate = new DateTimeImmutable()
    ) {
        //parent properties
        $this->id = $id;
        $this->created = $created;
        $this->lastUpdate = $lastUpdate;
    }
}
