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
    public string $uuid = '';

    /**
     * @var string User name.
     */
    public string $name = '';

    /**
     * @var string User description.
     */
    public ?string $description = '';

    /**
     * @var string User e-mail.
     */
    public ?string $email = '';

    /**
     * @var string User hashed password.
     */
    public string $password = '';

    /**
     * @var int It say if user is active or not.
     */
    public int $active = 0;

    /**
     * @var Password Password class for manage password.
     */
    private Password $passwordUtility;

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
        \settype($this->id, 'integer');
        \settype($this->active, 'integer');
        \settype($this->email, 'string');
        \settype($this->description, 'string');
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
