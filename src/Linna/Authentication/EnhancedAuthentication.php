<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

use Linna\Session\Session;

/**
 * Extend basic user authentication system with more security checks.
 */
class EnhancedAuthentication extends Authentication
{
    /**
     * Class Constructor.
     *
     * <pre><code class="php">use Linna\Authentication\EnhancedAuthentication;
     * use Linna\Authentication\EnhancedAuthenticationMapper;
     * use Linna\Authentication\Password;
     * use Linna\Session\Session;
     * use Linna\Storage\ExtendedPDO;
     * use Linna\Storage\StorageFactory;
     *
     * $session = new Session();
     * $password = new Password();
     * $pdo = (new StorageFactory('pdo', $options))->get();
     * $enhancedAuthenticationMapper = new EnhancedAuthenticationMapper($pdo);
     *
     * $enhancedAuthentication = new EnhancedAuthentication($session, $password, $enhancedAuthenticationMapper);
     * </code></pre>
     *
     * @param Session                               $session    Session class instance.
     * @param Password                              $password   Password class instance.
     * @param EnhancedAuthenticationMapperInterface $mapper     Implementation of EnhancedAuthenticationMapper.
     * @param array<int>                            $options    Class options.
     */
    public function __construct(
        Session $session,
        Password $password,
        private EnhancedAuthenticationMapperInterface $mapper,
        // options
        protected int $maxAttemptsForUserName = 5,
        protected int $maxAttemptsForSessionId = 10,
        protected int $maxAttemptsForIpAddress = 20,
        protected int $maxAttemptsForSecond = 40,
        protected int $banTimeInSeconds = 900
    ) {
        parent::__construct($session, $password);
    }

    /**
     * Return how many attemps are left for incorrect password.
     *
     * <pre><code class="php">$userName = 'root';
     * $enhancedAuthentication->getAttemptsLeftWithSameUser($userName);
     * </code></pre>
     *
     * @param string $userName User for which to retrieve login attempts.
     *
     * @return int Number of attempts with same user.
     */
    public function getAttemptsLeftWithSameUser(string $userName): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForUserName) - $this->mapper->fetchAttemptsWithSameUser($userName, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Return how many attemps are left for same session id.
     *
     * <pre><code class="php">$sessionId = '47u2hrm1n79u2ae1992vmhgpat';
     * $enhancedAuthentication->getAttemptsLeftWithSameSession($sessionId);
     * </code></pre>
     *
     * @param string $sessionId Session id for which to retrieve login attempts.
     *
     * @return int Number of attempts with same session.
     */
    public function getAttemptsLeftWithSameSession(string $sessionId): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForSessionId) - $this->mapper->fetchAttemptsWithSameSession($sessionId, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Return how many attemps are left for same ip.
     *
     * <pre><code class="php">$ipAddress = '192.168.0.15';
     * $enhancedAuthentication->getAttemptsLeftWithSameIp($ipAddress);
     * </code></pre>
     *
     * @param string $ipAddress Ip address for which to retrieve login attempts.
     *
     * @return int Number of attempts with same ip.
     */
    public function getAttemptsLeftWithSameIp(string $ipAddress): int
    {
        $attemptsLeft = ((int) $this->maxAttemptsForIpAddress) - $this->mapper->fetchAttemptsWithSameIp($ipAddress, $this->banTimeInSeconds);

        return \max(0, $attemptsLeft);
    }

    /**
     * Check if an user is banned from do login.
     *
     * <pre><code class="php">$userName = 'root';
     * $enhancedAuthentication->isUserBanned($userName);
     * </code></pre>
     *
     * @param string $userName User for which check if is banned.
     *
     * @return bool
     */
    public function isUserBanned(string $userName): bool
    {
        return !$this->getAttemptsLeftWithSameUser($userName);
    }

    /**
     * Check if a session id is banned from do login.
     *
     * <pre><code class="php">$sessionId = '47u2hrm1n79u2ae1992vmhgpat';
     * $enhancedAuthentication->isSessionBanned($sessionId);
     * </code></pre>
     *
     * @param string $sessionId Session id for wich check if is banned.
     *
     * @return bool
     */
    public function isSessionBanned(string $sessionId): bool
    {
        return !$this->getAttemptsLeftWithSameSession($sessionId);
    }

    /**
     * Check if an ip address is banned from do login.
     *
     * <pre><code class="php">$ipAddress = '192.168.0.15';
     * $enhancedAuthentication->isIpBanned($ipAddress);
     * </code></pre>
     *
     * @param string $ipAddress Ip address for wich check if is banned.
     *
     * @return bool
     */
    public function isIpBanned(string $ipAddress): bool
    {
        return !$this->getAttemptsLeftWithSameIp($ipAddress);
    }
}
