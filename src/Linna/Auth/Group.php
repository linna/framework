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
    private $users;

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
     * @param array $users
     * @param array $permission
     */
    public function __construct(array $users, array $permission)
    {
        $this->users = $users;
        $this->permission = $permission;

        //set required type
        settype($this->objectId, 'integer');
        settype($this->active, 'integer');
    }
    
    /**
     * Show users in group.
     * 
     * @return array
     */
    public function showUsers() : array
    {
        $groupUsers = $this->users;
        $users = [];

        foreach ($groupUsers as $gUser) {
            $users[] = $gUser->name;
        }

        return $users;
    }
    
    /**
     * Check if an user is in group.
     *  
     * @param string $user
     * @return bool
     */
    public function isUserInGroup(string $user) : bool
    {
        $groupUsers = $this->users;

        foreach ($groupUsers as $gUser) {
            if ($gUser->name === $user) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Show Group Permissions.
     *
     * @return array
     */
    public function showPermissions() : array
    {
        $groupPermissions = $this->permission;
        $permissions = [];

        foreach ($groupPermissions as $gPermission) {
            $permissions[] = $gPermission->name;
        }

        return $permissions;
    }

    /**
     * Check Group Permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can(string $permission) : bool
    {
        $groupPermissions = $this->permission;

        foreach ($groupPermissions as $gPermission) {
            if ($gPermission->name === $permission) {
                return true;
            }
        }

        return false;
    }
}
