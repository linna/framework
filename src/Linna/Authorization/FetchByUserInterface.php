<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use Linna\Authorization\EnhancedUser;

/**
 * Fetch By User Interface.
 *
 * Contain methods required to fetch permissions or roles by User.
 */
interface FetchByUserInterface
{
    /**
     * Fetch from user.
     *
     * From `EnhancedUser` instance as argument, this method must return an
     * array containing a `Permission|Role` object instance for every
     * `Permission|Role` owned by the given user.
     *
     * @param EnhancedUser $user The user which will be used to fetch.
     *
     * @return array<mixed> Permissions or roles permissions granted to the user.
     */
    public function fetchByUser(EnhancedUser $user): array;

    /**
     * Fetch from user.
     *
     * From user id as argument, this method must return an array containing
     * a `Permission|Role` object instance for every `Permission|Role` owned by
     * the given user.
     *
     * @param int $userId The user which will be used to fetch.
     *
     * @return array<mixed> Permissions or roles permissions granted to the user.
     */
    public function fetchByUserId(int $userId): array;

    /**
     * Fetch from a user.
     *
     * From user name as argument, this method must return an array containing
     * a `Permission|Role` object instance for every `Permission|Role` owned by
     * the given user.
     *
     * @param string $userName The user which will be used to fetch.
     *
     * @return array<mixed> Permissions or roles permissions granted to the user.
     */
    public function fetchByUserName(string $userName): array;
}
