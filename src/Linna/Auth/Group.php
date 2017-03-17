<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

use Linna\DataMapper\DomainObjectAbstract;

/**
 * Group.
 */
class Group extends DomainObjectAbstract
{
    /**
     * @var string Group name
     */
    public $name;

    /**
     * @var string Group description
     */
    public $description;

    /**
     * @var int It say if group is active or not
     */
    public $active = 0;

    /**
     * @var array Contain users in group
     */
    private $user;

    /**
     * @var array Contain permission of the group
     */
    private $permission;

    /**
     * @var string Last update
     */
    public $lastUpdate;

    /**
     * Constructor.
     *
     * @param array $user
     * @param array $permission
     */
    public function __construct(array $user, array $permission)
    {
        $this->user = $user;
        $this->permission = $permission;

        //set required type
        settype($this->objectId, 'integer');
        settype($this->active, 'integer');
    }

    public function addUser(User $user)
    {
    }

    public function removeUser(User $user)
    {
    }

    public function getUsers()
    {
    }

    public function hasUser() : bool
    {
    }

    public function getPermissions() : array
    {
    }

    public function addPermission(Permission $permission)
    {
    }

    public function addMultiplePermission(array $permission)
    {
    }

    public function removePermission(Permission $permission)
    {
    }
}
