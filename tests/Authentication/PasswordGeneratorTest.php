<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

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
    protected $password;

    /**
     * Setup.
     */
    public function setUp()
    {
        $this->password = new Password();
    }

    /**
     * Test password hash.
     */
    public function testPasswordHashAndVerify()
    {
        $hash = $this->password->hash('password');

        $this->assertTrue($this->password->verify('password', $hash));
    }

    /**
     * Test password hash and verify fail verify.
     */
    public function testPasswordHashAndFailVerify()
    {
        $hash = $this->password->hash('password');
        
        $this->assertFalse($this->password->verify('otherpassword', $hash));
    }

    /**
     * Test hash that need reash.
     */
    public function testHashThatNeedRehash()
    {
        $hash = password_hash('password', PASSWORD_DEFAULT, ['cost' => 9,]);

        $this->assertTrue($this->password->needsRehash($hash));
    }

    /**
     * Test hash that not need rehash.
     */
    public function testHashThatNotNeedRehash()
    {
        $hash = $this->password->hash('password');
        
        $this->assertFalse($this->password->needsRehash($hash));
    }

    /**
     * Test get hash info.
     */
    public function testGetHashInfo()
    {
        $hash = '$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO';

        $info = $this->password->getInfo($hash);

        $this->assertEquals('array', gettype($info));
        $this->assertEquals(1, $info['algo']);
    }

    /**
     * Test get bad hash info with bad hash.
     */
    public function testGetHashInfoWithBadHash()
    {
        $hash = 'badPaswordHash';

        $info = $this->password->getInfo($hash);

        $this->assertEquals('array', gettype($info));
        $this->assertEquals(0, $info['algo']);
    }
}
