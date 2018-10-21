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
 * Role.
 */
class Role extends DomainObjectAbstract
{
    use PermissionTrait;

    /**
     * @var string Group name
     */
    public $name = '';

    /**
     * @var string Group description
     */
    public $description = '';

    /**
     * @var int It say if group is active or not
     */
    public $active = 0;

    /**
     * @var array Contain users in group
     */
    private $users = [];

    /**
     * @var string Last update
     */
    public $lastUpdate = '';

    /**
     * Constructor.
     */
    public function __construct(array $users = [], array $permissions = [])
    {
        $this->users = $users;
        $this->permission = $permissions;

        //set required type
        settype($this->objectId, 'integer');
        settype($this->active, 'integer');
    }

    /**
     * Check if an user is in role.
     *
     * @param string $user
     *
     * @return bool
     */
    public function isUserInRole(string $user): bool
    {
        foreach ($this->users as $ownUser) {
            if ($ownUser->name === $user) {
                return true;
            }
        }

        return false;
    }
}
