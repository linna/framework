<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

use Linna\DataMapper\DomainObjectAbstract;

/**
 * Provide a basic user for authentication system.
 * This class is thinked to be used with PHP PDO,
 * but should be instantiated like a normal class.
 *
 * <pre><code class="php">$password = new Password();
 *
 * $userId = 1;
 *
 * $pdos = $pdo->prepare('SELECT user_id AS objectId,
 *     uuid,
 *     name,
 *     email,
 *     description,
 *     password,
 *     active,
 *     created,
 *     last_update AS lastUpdate
 *     FROM user WHERE user_id = :id');
 *
 * $pdos->bindParam(':id', $userId, \PDO::PARAM_INT);
 * $pdos->execute();
 *
 * //pdo return an User class instance
 * $user = $pdos->fetchObject('\Linna\Auth\User', [$password]);
 *
 * //var_dump result
 * //root
 * var_dump($user->name);
 * </code></pre>
 */
class User extends DomainObjectAbstract
{
    /**
     * @var string Universal unique identifier.
     */
    public $uuid = '';

    /**
     * @var string User name.
     */
    public $name = '';

    /**
     * @var string User description.
     */
    public $description = '';

    /**
     * @var string User e-mail.
     */
    public $email = '';

    /**
     * @var string User hashed password.
     */
    public $password = '';

    /**
     * @var int It say if user is active or not.
     */
    public $active = 0;

    /**
     * @var string User creation date.
     */
    public $created = '';

    /**
     * @var string Last update.
     */
    public $lastUpdate = '';

    /**
     * @var Password Password class for manage password.
     */
    private $passwordUtility;

    /**
     * Class Constructor.
     *
     * <pre><code class="php">$password = new Password();
     *
     * $user = new User($password);
     * </code></pre>
     *
     * @param Password $password Password class instance.
     */
    public function __construct(Password $password)
    {
        $this->passwordUtility = $password;

        //set required type
        //do type conversion because PDO doesn't return any original type from db :(.
        \settype($this->rId, 'integer');
        \settype($this->objectId, 'integer');
        \settype($this->active, 'integer');
    }

    /**
     * Set new user password without do any check.
     *
     * <pre><code class="php">$user->setPassword('newPassword');</code></pre>
     *
     * @param string $newPassword User new password.
     *
     * @return void
     */
    public function setPassword(string $newPassword): void
    {
        //hash provided password
        $this->password = $this->passwordUtility->hash($newPassword);
    }

    /**
     * Change user password only after check old password.
     *
     * <pre><code class="php">$user->changePassword('newPassword', 'oldPassword');</code></pre>
     *
     * @param string $newPassword   User new password.
     * @param string $oldPassword   User old password.
     *
     * @return bool
     */
    public function changePassword(string $newPassword, string $oldPassword): bool
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
