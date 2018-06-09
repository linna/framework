<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Session Test
 *
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
        $this->session = new Session(['expire' => 1800]);
    }

    /**
     * Test Session Start.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     */
    public function testSessionStart()
    {
        $session = $this->session;

        $this->assertEquals(1, $session->status);
        
        $session->start();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        
        $this->assertEquals(2, $session->status);
        
        $session->destroy();
    }
    
    /**
     * Test session commit.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     */
    public function testSessionCommit()
    {
        $session = $this->session;
        $session->start();
        
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        $this->assertEquals($session->id, session_id());
        
        $session['fooData'] = 'fooData';
        
        $session->commit();
        
        $session->start();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        
        $this->assertEquals($session->id, session_id());
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
    }
    
    /**
     * Test session destroy.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     */
    public function testSessionDestroy()
    {
        $session = $this->session;

        $session->start();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        
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
     * @requires extension xdebug
     * @runInSeparateProcess
     */
    public function testSessionRegenerate()
    {
        $session = $this->session;

        $session->start();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        $cookieNameBefore = $this->getCookieValue($this->getCookieValues());
        
        $session['fooData'] = 'fooData';
        
        $sessionIdBefore = session_id();
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdBefore, $session->id);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->regenerate();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        $cookieNameAfter = $this->getCookieValue($this->getCookieValues());
        
        $sessionIdAfter = session_id();
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdAfter, $session->id);
        $this->assertNotEquals($sessionIdAfter, $sessionIdBefore);
        $this->assertNotEquals($cookieNameBefore, $cookieNameAfter);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
    }
    
    /**
     * Wrong arguments router class provider.
     *
     * @return array
     */
    public function sessionTimeProvider() : array
    {
        return [
            [1797, true],
            [1798, true],
            [1799, true],
            [1800, false],
            [1801, false],
            [1802, false]
        ];
    }
    
    /**
     * Test session expired.
     *
     * @dataProvider sessionTimeProvider
     * @requires extension xdebug
     * @runInSeparateProcess
     */
    public function testSessionExpired(int $time, bool $equals)
    {
        $session = $this->session;

        $session->start();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        $cookieNameBefore = $this->getCookieValue($this->getCookieValues());
        
        $session_id = $session->id;

        $session->time = $session->time - $time;

        $session->commit();

        $session->start();
        $this->assertTrue($this->checkCookieTime(60, $this->getCookieValues()));
        $cookieNameAfter = $this->getCookieValue($this->getCookieValues());
        
        $session2_id = $session->id;

        if ($equals) {
            $this->assertEquals($session_id, $session2_id);
            $this->assertEquals($cookieNameBefore, $cookieNameAfter);
        } else {
            $this->assertNotEquals($session_id, $session2_id);
            $this->assertNotEquals($cookieNameBefore, $cookieNameAfter);
        }
            
        $this->assertEquals(2, $session->status);

        $session->destroy();
    }
    
    /**
     * Test create and get with property access.
     *
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
    
    /**
     * Test create and get with array access trait method.
     */
    public function testCreateAndGetWithTraitMethod()
    {
        $this->session->offsetSet('testData', 'foo');

        $this->assertEquals($this->session->offsetGet('testData'), 'foo');
    }

    /**
     * Test delete and isset with array access trait method.
     */
    public function testDeleteAndIssetWithTraitMethod()
    {
        $this->session->offsetUnset('testData');

        $this->assertFalse($this->session->offsetExists('testData'));
    }

    /**
     * Test get unstored value with array access trait method.
     */
    public function testGetUnstoredValueWithTraitMethod()
    {
        $this->assertFalse($this->session->offsetGet('testData'));
    }
    
    /**
     * Get session cookie set values.
     *
     * @requires extension xdebug
     */
    public function getCookieValues() : array
    {
        $headers = xdebug_get_headers();
        $cookie = [];
       
        foreach ($headers as $value) {
            if (strstr($value, 'Set-Cookie:') !== false) {
                $cookie[] = explode(';', str_replace('Set-Cookie: ', "", $value));
            }
        }
       
        $cleanedCookie = [];
        
        foreach ($cookie as $values) {
            $tmpCookie = [];
            
            foreach ($values as $value) {
                $explode = explode('=', ltrim(rtrim($value)));
                
                $name = ltrim(rtrim($explode[0]));
                
                $tmpCookie[$name] = (isset($explode[1])) ? $explode[1] : null;
            }
            
            $cleanedCookie[] = $tmpCookie;
        }
        
        return $cleanedCookie;
    }
    
    /**
     * Check if cookie is valid for the passed time.
     *
     * @param int $time
     * @param array $cookieArray
     * @return bool
     */
    public function checkCookieTime(int $time, array $cookieArray) : bool
    {
        $last = count($cookieArray) -1;
        
        $cookieTime = strtotime($cookieArray[$last]['expires']);
        
        if ($cookieTime > time() + $time) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get the cookie value;
     *
     * @param array $cookieArray
     */
    public function getCookieValue(array $cookieArray) : string
    {
        $last = count($cookieArray) -1;
        
        $sessionName = session_name();
        
        return $cookieArray[$last][$sessionName];
    }
}
