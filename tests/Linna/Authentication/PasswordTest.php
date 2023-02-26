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

use PHPUnit\Framework\TestCase;

/**
 * Password Test.
 */
class PasswordTest extends TestCase
{
    /** @var Password The password class. */
    protected static Password $password;

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
        $this->assertTrue(self::$password->verify('password', self::$password->hash('password')));
    }

    /**
     * Test password hash and verify fail verify.
     *
     * @return void
     */
    public function testPasswordHashAndFailVerify(): void
    {
        $this->assertFalse(self::$password->verify('otherpassword', self::$password->hash('password')));
    }

    /**
     * Test hash that need reash.
     *
     * @return void
     */
    public function testHashThatNeedRehash(): void
    {
        $passwordOther = new Password(SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE);

        $this->assertTrue($passwordOther->needsRehash(self::$password->hash('password')));
    }

    /**
     * Test hash that not need rehash.
     *
     * @return void
     */
    public function testHashThatNotNeedRehash(): void
    {
        $this->assertFalse(self::$password->needsRehash(self::$password->hash('password')));
    }

    /**
     * Options provider.
     * 
     * @return array
     */
    public static function optionsProvider(): array
    {
        return [
            [SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE, 65536, 2, 1],
            [SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE, 262144, 3, 1],
            [SODIUM_CRYPTO_PWHASH_OPSLIMIT_SENSITIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_SENSITIVE, 1048576, 4, 1],
        ];
    }

    /**
     * Test get hash info.
     *
     * @dataProvider optionsProvider
     *
     * @return void
     */
    public function testGetHashInfo(int $opsLimit, int $memLimit, int $memExp, int $timeExp, int $threads): void
    {
        $password = new Password($opsLimit, $memLimit);

        //info
        $info = $password->getInfo($password->hash('password'));

        $this->assertIsArray($info);
        $this->assertArrayHasKey('algo', $info);
        $this->assertArrayHasKey('algoName', $info);
        $this->assertArrayHasKey('options', $info);

        //info options
        $options = $info['options'];

        $this->assertIsArray($options);
        $this->assertArrayHasKey('memory_cost', $options);
        $this->assertArrayHasKey('time_cost', $options);
        $this->assertArrayHasKey('threads', $options);


        $this->assertSame('argon2id', $info['algo']);
        $this->assertSame('argon2id', $info['algoName']);

        $this->assertSame($memExp, $options['memory_cost']);
        $this->assertSame($timeExp, $options['time_cost']);
        $this->assertSame($threads, $options['threads']);
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

        $this->assertIsArray($info);
        $this->assertArrayHasKey('algo', $info);
        $this->assertArrayHasKey('algoName', $info);
        $this->assertArrayHasKey('options', $info);

        $this->assertSame(null, $info['algo']);
        $this->assertSame('unknown', $info['algoName']);
        $this->assertSame([], $info['options']);
    }
}
