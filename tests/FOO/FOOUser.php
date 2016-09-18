<?php

/**
 * Linna App
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

use Linna\DataMapper\DomainObjectAbstract;
use Linna\Auth\Password;

/**
 * Rapresent user
 *
 */
class FOOUser extends DomainObjectAbstract
{
    /**
     * @var string $name User name
     */
    public $name;

    /**
     * @var string $description User description
     */
    public $description;

    /**
     * @var string $password User hashed password
     */
    public $password;

    /**
     * @var int $active It say if user is active or not
     */
    public $active = 0;

    /**
     * @var string $created User creation date
     */
    public $created;

    /**
     * @var string $last_update Last user update
     */
    public $lastUpdate;
    
    /**
     *
     * @var object $passwordUtility Password class for manage password
     */
    private $passwordUtility;
    
    /**
     * Constructor
     *
     * Do type conversion because PDO doesn't return any original type from db :(
     */
    public function __construct($password)
    {
        $this->passwordUtility = $password;
        
        settype($this->objectId, 'integer');
        settype($this->active, 'integer');
    }

    /**
     * Set new user password without do any check
     *
     * @param string $newPassword
     *
     */
    public function setPassword($newPassword)
    {
        $passUtil = new Password();

        $hash = $passUtil->hash($newPassword);

        $this->password = $hash;
    }

    /**
     * Change user password only after check old password
     *
     * @param string $newPassword New user password
     * @param string $oldPassword Old user password
     *
     * @return bool
     *
     */
    public function chagePassword($newPassword, $oldPassword)
    {
        $passUtil = new Password();

        $hash = $passUtil->hash($newPassword);

        if ($passUtil->verify($oldPassword, $this->password)) {
            $this->password = $hash;

            return true;
        }

        return false;
    }
}
