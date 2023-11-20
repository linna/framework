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
 * RoleExtended domain object.
 */
class RoleExtended extends Role
{
    use PermissionTrait;
    use UserTrait;

    /**
     * Constructor.
     *
     * @param string       $name        The name of the role.
     * @param string       $description The description of the role.
     * @param integer      $active      Specify is the role is atctive.
     * @param array<mixed> $users       Users in role.
     * @param array<mixed> $permissions Permissions granted by the role.
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

        //users in role
        array $users = [],

        //permissions in role
        array $permissions = [],

        //creation datetime
        ?DateTimeImmutable $created = new DateTimeImmutable(),

        //last updated datetime
        ?DateTimeImmutable $lastUpdate = new DateTimeImmutable()
    ) {
        //initialize parent
        parent::__construct(
            id:              $id,
            name:            $name,
            description:     $description,
            active:          $active,
            created:         $created,
            lastUpdate:      $lastUpdate
        );

        //from user trait
        $this->user = $users;

        //from permission trait
        $this->permission = $permissions;
    }
}
