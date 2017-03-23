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
 * User.
 */
class User extends DomainObjectAbstract
{
    /**
     * @var string User name
     */
    public $name;

    /**
     * @var string User description
     */
    public $description;

    /**
     * @var string User e-mail
     */
    public $email;

    /**
     * @var string User hashed password
     */
    public $password;

    /**
     * @var int It say if user is active or not
     */
    public $active = 0;

    /**
     * @var string User creation date
     */
    public $created;

    /**
     * @var string Last update
     */
    public $lastUpdate;

    /**
     * @var object Password class for manage password
     */
    private $passwordUtility;

    /**
     * Constructor
     * Do type conversion because PDO doesn't return any original type from db :(.
     *
     * @param Password $password
     */
    public function __construct(Password $password)
    {
        $this->passwordUtility = $password;

        //set required type
        settype($this->objectId, 'integer');
        settype($this->active, 'integer');
    }

    /**
     * Set new user password without do any check.
     *
     * @param string $newPassword
     */
    public function setPassword(string $newPassword)
    {
        //hash provided password
        $this->password = $this->passwordUtility->hash($newPassword);
    }

    /**
     * Change user password only after check old password.
     *
     * @param string $newPassword
     * @param string $oldPassword
     *
     * @return bool
     */
    public function chagePassword(string $newPassword, string $oldPassword) : bool
    {
        //verfy password match
        if ($this->passwordUtility->verify($oldPassword, $this->password)) {

            //if match set new password
            $this->password = $this->passwordUtility->hash($newPassword);

            return true;
        }

        return false;
    }
}
