<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected $session;
    
    public function setUp()
    {
        $this->session = new Session(['expire' => 5]);
    }
    
    public function testPropertyAccessCreateAndGet()
    {
        $this->session->testData = 'foo';
        
        $this->assertEquals($this->session->testData, 'foo');
    }
    
    public function testPropertyAccessDeleteAndIsset()
    {
        unset($this->session->testData);
        
        $this->assertEquals(false, isset($this->session->testData));
    }        

    public function testPropertyAccessGetUnstored()
    {
        $this->assertEquals($this->session->testData, false);
    }
    
    public function testArrayAccessCreateAndGet()
    {
        $this->session['testData'] = 'foo';
        
        $this->assertEquals($this->session['testData'], 'foo');
    }

    public function testArrayAccessDeleteAndIsset()
    {
        unset($this->session['testData']);
        
        $this->assertEquals(false, isset($this->session['testData']));
    }
    
    public function testArrayAccessGetUnstored()
    {
        $this->assertEquals($this->session['testData'], false);
    }
}
