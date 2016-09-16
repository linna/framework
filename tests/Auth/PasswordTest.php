<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Auth\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testHash()
    {
        $password = new Password();
        
        $hash = $password->hash('password');
        
        $result = $password->verify('password', $hash);
        
        $this->assertEquals(true, $result);
    }
    
    public function testHashFail()
    {
        $password = new Password();
        
        $hash = $password->hash('password');
        
        $result = $password->verify('otherpassword', $hash);
        
        $this->assertEquals(false, $result);
    }
    
    public function testNeedRehash()
    {
        $password = new Password();
        
        $options = [
            'cost' => 9
        ];
        
        $hash = password_hash('password', PASSWORD_DEFAULT, $options);
        
        $result = $password->needsRehash($hash);
        
        $this->assertEquals(true, $result);
    }
    
    public function testNoNeedRehash()
    {
        $password = new Password();
        
        $hash = $password->hash('password');
        
        $result = $password->needsRehash($hash);
        
        $this->assertEquals(false, $result);
    }
}
