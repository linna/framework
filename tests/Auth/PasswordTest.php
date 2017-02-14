<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Auth\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    protected $password;

    public function setUp()
    {
        $this->password = new Password();
    }

    public function testHash()
    {
        $hash = $this->password->hash('password');
        $result = $this->password->verify('password', $hash);

        $this->assertEquals(true, $result);
    }

    public function testHashFail()
    {
        $hash = $this->password->hash('password');
        $result = $this->password->verify('otherpassword', $hash);

        $this->assertEquals(false, $result);
    }

    public function testNeedRehash()
    {
        $options = [
            'cost' => 9,
        ];

        $hash = password_hash('password', PASSWORD_DEFAULT, $options);

        $result = $this->password->needsRehash($hash);

        $this->assertEquals(true, $result);
    }

    public function testNoNeedRehash()
    {
        $hash = $this->password->hash('password');
        $result = $this->password->needsRehash($hash);

        $this->assertEquals(false, $result);
    }

    public function testGetInfo()
    {
        $hash = '$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO';

        $info = $this->password->getInfo($hash);

        $this->assertEquals('array', gettype($info));
        $this->assertEquals(1, $info['algo']);
    }

    public function testNoGetInfo()
    {
        $hash = 'badPaswordHash';

        $info = $this->password->getInfo($hash);

        $this->assertEquals('array', gettype($info));
        $this->assertEquals(0, $info['algo']);
    }
}
