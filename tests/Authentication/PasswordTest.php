<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Authentication\Password;
use PHPUnit\Framework\TestCase;

/**
 * Password Test.
 */
class PasswordTest extends TestCase
{
    /**
     * @var Password The password class.
     */
    protected static $password;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$password = new Password();
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$password = null;
    }

    /**
     * Test password hash.
     *
     * @return void
     */
    public function testPasswordHashAndVerify(): void
    {
        $hash = self::$password->hash('password');

        $this->assertTrue(self::$password->verify('password', $hash));
    }

    /**
     * Test password hash and verify fail verify.
     *
     * @return void
     */
    public function testPasswordHashAndFailVerify(): void
    {
        $hash = self::$password->hash('password');

        $this->assertFalse(self::$password->verify('otherpassword', $hash));
    }

    /**
     * Test hash that need reash.
     *
     * @return void
     */
    public function testHashThatNeedRehash(): void
    {
        $hash = \password_hash('password', PASSWORD_DEFAULT, ['cost' => 9]);

        $this->assertTrue(self::$password->needsRehash($hash));
    }

    /**
     * Test hash that not need rehash.
     *
     * @return void
     */
    public function testHashThatNotNeedRehash(): void
    {
        $hash = self::$password->hash('password');

        $this->assertFalse(self::$password->needsRehash($hash));
    }

    /**
     * Test get hash info.
     *
     * @return void
     */
    public function testGetHashInfo(): void
    {
        $hash = '$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO';

        $info = self::$password->getInfo($hash);

        $this->assertEquals('array', \gettype($info));

        //fix for php 7.4
        if (PHP_MINOR_VERSION === 4) {
            $this->assertEquals('2y', $info['algo']);
            return;
        }

        $this->assertEquals(1, $info['algo']);
    }

    /**
     * Test get bad hash info with bad hash.
     *
     * @return void
     */
    public function testGetHashInfoWithBadHash(): void
    {
        $hash = 'badPaswordHash';

        $info = self::$password->getInfo($hash);

        $this->assertEquals('array', \gettype($info));
        $this->assertEquals(0, $info['algo']);
    }

    /**
     * Test constructor with invalid password algorithm name
     */
    public function testConstructorWithInvalidPasswordAlgorithmConstant()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The password algorithm invalid_algo is invalid');

        new Password('invalid_algo');
    }
}
