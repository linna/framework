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

/**
 * Session Test
 */
class SessionTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected $session;

    /**
     * Setup.
     */
    public function setUp()
    {
        $this->session = new Session(['expire' => 5]);
    }

    /**
     * Test Session Start.
     * 
     * @runInSeparateProcess
     */
    public function testSessionStart()
    {
        $session = $this->session;

        $this->assertEquals(1, $session->status);

        $session->start();

        $this->assertEquals(2, $session->status);

        $session->destroy();
    }
    
    /**
     * Test session commit.
     * 
     * @runInSeparateProcess
     */
    public function testSessionCommit()
    {
        $session = $this->session;
        $session->start();
        
        $this->assertEquals($session->id, session_id());
        
        $session['fooData'] = 'fooData';
        
        $session->commit();
        
        $session->start();
        
        $this->assertEquals($session->id, session_id());
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
    }
    
    /**
     * Test session destroy.
     *
     * @runInSeparateProcess
     */
    public function testSessionDestroy()
    {
        $session = $this->session;

        $session->start();
        $session['fooData'] = 'fooData';
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals(session_id(), $session->id);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
        
        $this->assertEquals(1, $session->status);
        $this->assertEquals('', $session->id);
        $this->assertFalse($session['fooData']);
    }
    
    /**
     * Test session regenerate.
     * 
     * @runInSeparateProcess
     */
    public function testSessionRegenerate()
    {
        $session = $this->session;

        $session->start();
        $session['fooData'] = 'fooData';
        
        $sessionIdBefore = session_id();
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdBefore, $session->id);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->regenerate();
        
        $sessionIdAfter = session_id();
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdAfter, $session->id);
        $this->assertNotEquals($sessionIdAfter, $sessionIdBefore);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
    }
    
    /**
     * Test session expired.
     * 
     * @runInSeparateProcess
     */
    public function testSessionExpired()
    {
        $session = $this->session;

        $session->start();

        $session_id = $session->id;

        $session->time = $session->time - 1800;

        $session->commit();

        $session->start();

        $session2_id = $session->id;

        $this->assertNotEquals($session_id, $session2_id);
        $this->assertEquals(2, $session->status);

        $session->destroy();
    }
    
    /**
     * Test create and get with property access.
     */
    public function testCreateAndGetWithPropertyAccess()
    {
        $this->session->testData = 'foo';

        $this->assertEquals($this->session->testData, 'foo');
    }

    /**
     * Test delete and isset with property access.
     */
    public function testDeleteAndIssetWithPropertyAccess()
    {
        unset($this->session->testData);

        $this->assertFalse(isset($this->session->testData));
    }

    /**
     * Test get unstored value with property access.
     */
    public function testGetUnstoredValueWithPropertyAccess()
    {
        $this->assertFalse($this->session->testData);
    }

    /**
     * Test create and get with array access.
     */
    public function testCreateAndGetWithArrayAccess()
    {
        $this->session['testData'] = 'foo';

        $this->assertEquals($this->session['testData'], 'foo');
    }

    /**
     * Test delete and isset with array access.
     */
    public function testDeleteAndIssetWithArrayAccess()
    {
        unset($this->session['testData']);

        $this->assertFalse(isset($this->session['testData']));
    }

    /**
     * Test get unstored value with array access.
     */
    public function testGetUnstoredValueWithArrayAccess()
    {
        $this->assertFalse($this->session['testData']);
    }
}
