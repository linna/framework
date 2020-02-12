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

use Linna\Session\Session;

/**
 * Extend basic user authentication system with more security checks.
 */
class EnhancedAuthentication extends Authentication
{
    /**
     * @var int Max attempts for user name.
     */
    protected int $maxAttemptsForUserName = 5;

    /**
     * @var int Max attempts for session id.
     */
    protected int $maxAttemptsForSessionId = 10;

    /**
     * @var int Max attempts for ip address.
     */
    protected int $maxAttemptsForIpAddress = 20;

    /**
     * @var int Max attempts for second.
     */
    protected int $maxAttemptsForSecond = 40;

    /**
     * @var int Ban time in seconds.
     */
    protected int $banTimeInSeconds = 900;

    /**
     * @var EnhancedAuthenticationMapperInterface Enhanced Authentication Mapper
     */
    private EnhancedAuthenticationMapperInterface $enhancedAuthenticationMapper;

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
     * @param Session                               $session                        Session class instance.
     * @param Password                              $password                       Password class instance.
     * @param EnhancedAuthenticationMapperInterface $enhancedAuthenticationMapper   Implementation of EnhancedAuthenticationMapper.
     * @param array                                 $options                        Class options.
     */
    public function __construct(
        Session $session,
        Password $password,
        EnhancedAuthenticationMapperInterface $enhancedAuthenticationMapper,
        array $options = []
    ) {
        parent::__construct($session, $password);

        $this->enhancedAuthenticationMapper = $enhancedAuthenticationMapper;

        [
            'maxAttemptsForUserName'  => $this->maxAttemptsForUserName,
            'maxAttemptsForSessionId' => $this->maxAttemptsForSessionId,
            'maxAttemptsForIpAddress' => $this->maxAttemptsForIpAddress,
            'maxAttemptsForSecond'    => $this->maxAttemptsForSecond,
            'banTimeInSeconds'        => $this->banTimeInSeconds
        ] = \array_replace_recursive([
            'maxAttemptsForUserName'  => $this->maxAttemptsForUserName,
            'maxAttemptsForSessionId' => $this->maxAttemptsForSessionId,
            'maxAttemptsForIpAddress' => $this->maxAttemptsForIpAddress,
            'maxAttemptsForSecond'    => $this->maxAttemptsForSecond,
            'banTimeInSeconds'        => $this->banTimeInSeconds
        ], $options);
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
        $attemptsLeft = ((int) $this->maxAttemptsForUserName) - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameUser($userName, $this->banTimeInSeconds);

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
        $attemptsLeft = ((int) $this->maxAttemptsForSessionId) - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameSession($sessionId, $this->banTimeInSeconds);

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
        $attemptsLeft = ((int) $this->maxAttemptsForIpAddress) - $this->enhancedAuthenticationMapper->fetchAttemptsWithSameIp($ipAddress, $this->banTimeInSeconds);

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
