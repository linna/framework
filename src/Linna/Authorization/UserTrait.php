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

/**
 * User Trait.
 *
 * <p>Use it to add the user functionality to a class.</p>
 */
trait UserTrait
{
    /** @var array<mixed> Users. */
    protected array $user = [];

    /**
     * Check if the class that use the trait has a user, use the User instance.
     *
     * @param User $user The user as <code>User<code> object which will be checked.
     *
     * @return bool True if has the user, false otherwise.
     */
    public function hasUser(User $user): bool
    {
        return $this->hasUserById($user->getId());
    }

    /**
     * Check if the class that use the trait has a user, use the user id.
     *
     * @param null|int|string $userId The user as user id or uuid which will be checked.
     *
     * @return bool True if has the user, false otherwise.
     */
    public function hasUserById(null|int|string $userId): bool
    {
        //if (isset($this->user[$userId])) {
        //    return true;
        //}

        if (\in_array($userId, \array_column($this->user, 'id'), true)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the class that use the trait has a user, use the user name.
     *
     * @param string $userName The user as user name which will be checked.
     *
     * @return bool True if has the user, false otherwise.
     */
    public function hasUserByName(string $userName): bool
    {
        //if (isset($this->user[$userName])) {
        //    return true;
        //}

        if (\in_array($userName, \array_column($this->user, 'name'), true)) {
            return true;
        }

        return false;
    }
}
