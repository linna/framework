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

/**
 * Permission Extended domain object.
 */
class PermissionExtended extends Permission
{
    use UserTrait;
    use RoleTrait;

    /**
     * Class Constructor.
     *
     * @param null|int|string        $id          Permission id.
     * @param string                 $name        Permission name.
     * @param string                 $description Permission description.
     * @param integer                $inherited   Specify if the permission is inherited from a group.
     * @param array<User>            $users       Users who hold the specific permission.
     * @param array<Role>            $roles       Roles who hold the specific permission.
     * @param DateTimeImmutable|null $created     Creation datetime.
     * @param DateTimeImmutable|null $lastUpdate  Last updated datetime.
     */
    public function __construct(
        //permission id
        null|int|string $id = null,

        /** @var string Permission name. */
        public string $name = '',

        /** @var string Permission description. */
        public string $description = '',

        /** @var int Id of the group from which the permission was inherited. */
        public int $inherited = 0,

        //users own permission
        array $users = [],

        //roles own permission
        array $roles = [],

        //creation datetime
        ?DateTimeImmutable $created = new DateTimeImmutable(),

        //last updated datetime
        ?DateTimeImmutable $lastUpdate = new DateTimeImmutable()
    ) {
        //initialize parent
        parent::__construct(
            id:          $id,
            name:        $name,
            description: $description,
            inherited:   $inherited,
            created:     $created,
            lastUpdate:  $lastUpdate
        );

        //from user trait
        $this->user = $users;

        //from role trait
        $this->role = $roles;
    }
}
