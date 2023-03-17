<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use DateTimeImmutable;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\StorageFactory;
use Linna\TestHelper\Pdo\PdoOptionsFactory;
use PHPUnit\Framework\TestCase;

/**
 * Login Attempt Test.
 */
class LoginAttemptTest extends TestCase
{
    /** @var ExtendedPDO Persistent storage connection. */
    protected static ExtendedPDO $pdo;

    /** @var EnhancedAuthenticationMapper The enhanced authentication mapper class */
    protected static EnhancedAuthenticationMapper $enhancedAuthenticationMapper;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $pdo = (new StorageFactory('pdo', PdoOptionsFactory::getOptions()))->get();
        $enhancedAuthenticationMapper = new EnhancedAuthenticationMapper($pdo);

        self::$pdo = $pdo;
        self::$enhancedAuthenticationMapper = $enhancedAuthenticationMapper;

        self::loginClean();
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::loginClean();
    }

    /**
     * Remove record from login_attemp table.
     *
     * @return void
     */
    protected static function loginClean(): void
    {
        self::$enhancedAuthenticationMapper->deleteOldLoginAttempts(-86400);
    }

    /**
     * Test login Attempt.
     *
     * @return void
     */
    public function testLoginAttempt(): void
    {
        $loginAttempt = new LoginAttempt(
            userName:   'root',
            sessionId:  'mbvi2lgdpcj6vp3qemh2estei2',
            ipAddress:  '192.168.1.2',
            when:       \date_create_immutable()
        );

        $this->assertInstanceOf(LoginAttempt::class, $loginAttempt);
        $this->assertSame(false, $loginAttempt->hasId());
        $this->assertSame(null, $loginAttempt->getId());
        $this->assertSame('root', $loginAttempt->userName);
        $this->assertSame('mbvi2lgdpcj6vp3qemh2estei2', $loginAttempt->sessionId);
        $this->assertInstanceOf(DateTimeImmutable::class, $loginAttempt->when);
        $this->assertSame(null, $loginAttempt->created);
        $this->assertSame(null, $loginAttempt->lastUpdate);

        self::$enhancedAuthenticationMapper->save($loginAttempt);

        $this->assertSame(true, $loginAttempt->hasId());

        $loginAttemptFromDB = self::$enhancedAuthenticationMapper->fetchById($loginAttempt->getId());

        $this->assertInstanceOf(LoginAttempt::class, $loginAttemptFromDB);
        $this->assertSame($loginAttempt->getId(), $loginAttemptFromDB->getId());
    }
}
