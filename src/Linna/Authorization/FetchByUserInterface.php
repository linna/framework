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

use Linna\Authorization\EnhancedUser;

/**
 * Fetch By User Interface
 * Contain methods required from fetching by User.
 */
interface FetchByUserInterface
{
    /**
     * Fetch from user.
     * From EnhancedUser instance as argument, this method must return an array containing
     * a Permission|Role object instance for every Permission|Role owned by the
     * given user.
     *
     * @param EnhancedUser $user
     *
     * @return array<mixed>
     */
    public function fetchByUser(EnhancedUser $user): array;

    /**
     * Fetch from user.
     * From user id as argument, this method must return an array containing
     * a Permission|Role object instance for every Permission|Role owned by the
     * given user.
     *
     * @param int $userId
     *
     * @return array<mixed>
     */
    public function fetchByUserId(int $userId): array;

    /**
     * Fetch from a user.
     * From user name as argument, this method must return an array containing
     * a Permission|Role object instance for every Permission|Role owned by the
     * given user.
     *
     * @param string $userName
     *
     * @return array<mixed>
     */
    public function fetchByUserName(string $userName): array;
}
