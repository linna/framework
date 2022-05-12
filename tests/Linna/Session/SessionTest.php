<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Session;

//use Linna\Session\Session;
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
    protected static Session $session;

    /**
     * Set up before class.
     *
     * @requires extension memcached
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$session = new Session([
            'expire'         => 1800,
            'cookieDomain'   => 'https://linna.tools',
            'cookiePath'     => '/app',
            'cookieSecure'   => true
        ]);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$session = null;
    }

    /**
     * Test session start.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionStart(): void
    {
        $session = self::$session;

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);

        //check for session parameters
        $this->assertSame('linna_session', \session_name());

        $cookieParams = \session_get_cookie_params();

        $this->assertIsInt($cookieParams['lifetime']);
        $this->assertSame(1800, $cookieParams['lifetime']);

        $this->assertIsString($cookieParams['path']);
        $this->assertSame('/app', $cookieParams['path']);

        $this->assertIsString($cookieParams['domain']);
        $this->assertSame('https://linna.tools', $cookieParams['domain']);

        $this->assertIsBool($cookieParams['secure']);
        $this->assertTrue($cookieParams['secure']);

        $this->assertIsBool($cookieParams['httponly']);
        $this->assertTrue($cookieParams['httponly']);

        $this->cookieCheck($this->getCookieValues(), $session);
    }

    /**
     * Test session start with already started session.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionStartWithAlreadyStartedSession(): void
    {
        $session = self::$session;

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);

        $this->cookieCheck($this->getCookieValues(), $session);

        $session->start();

        $this->cookieCheck($this->getCookieValues(), $session);

        $session->destroy();
    }

    /**
     * Test session commit.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionCommit(): void
    {
        $session = self::$session;

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);
        $this->assertSame($session->id, \session_id());
        $this->cookieCheck($this->getCookieValues(), $session);

        $session['fooData'] = 'fooData';

        $session->commit();

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);
        $this->assertSame($session->id, \session_id());
        $this->assertSame('fooData', $session['fooData']);
        $this->cookieCheck($this->getCookieValues(), $session);

        $session->destroy();
    }

    /**
     * Test session destroy.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionDestroy(): void
    {
        $session = self::$session;
        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);

        $this->cookieCheck($this->getCookieValues(), $session);

        $session['fooData'] = 'fooData';

        $this->assertSame(\session_id(), $session->id);
        $this->assertSame('fooData', $session['fooData']);

        $session->destroy();

        $cookie = $this->getCookieValues();

        $this->assertSame($cookie['linna_session'], 'NothingToSeeHere.');

        $cookieExpires = \strtotime($cookie['expires']);
        $resultExpires = \strtotime(\date(DATE_COOKIE, \time()));

        $this->assertSame($cookieExpires, $resultExpires);
        $this->assertSame($cookie['Max-Age'], '0');

        $this->assertSame(1, $session->status);
        $this->assertSame('', $session->id);
        $this->assertNull($session['fooData']);
    }

    /**
     * Test session regenerate.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionRegenerate(): void
    {
        $session = self::$session;

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);

        $this->cookieCheck($this->getCookieValues(), $session);

        $cookieValueBefore = $this->getCookieValue($this->getCookieValues());

        $session['fooData'] = 'fooData';

        $sessionIdBefore = \session_id();

        $this->assertSame(2, $session->status);
        $this->assertSame($sessionIdBefore, $session->id);
        $this->assertSame('fooData', $session['fooData']);

        $session->regenerate();

        $this->cookieCheck($this->getCookieValues(), $session);

        $cookieValueAfter = $this->getCookieValue($this->getCookieValues());

        $this->assertSame(2, $session->status);
        $this->assertSame(\session_id(), $session->id);
        $this->assertNotEquals(\session_id(), $sessionIdBefore);
        $this->assertNotEquals($cookieValueBefore, $cookieValueAfter);
        $this->assertSame('fooData', $session['fooData']);

        $session->destroy();
    }

    /**
     * Wrong arguments router class provider.
     *
     * @return array
     */
    public function sessionTimeProvider(): array
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
     *
     * @param int  $time
     * @param bool $equals
     *
     * @return void
     */
    public function testSessionExpired(int $time, bool $equals): void
    {
        $session = self::$session;

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);

        $this->cookieCheck($this->getCookieValues(), $session);

        $cookieValueBefore = $this->getCookieValue($this->getCookieValues());

        $session_id = $session->id;

        $session->time = $session->time - $time;

        $session->commit();

        $this->assertSame(1, $session->status);

        $session->start();

        $this->assertSame(2, $session->status);

        $cookieValueAfter = $this->getCookieValue($this->getCookieValues());

        $session2_id = $session->id;

        if ($equals) {
            $this->cookieCheck($this->getCookieValues(), $session);

            $this->assertSame($session_id, $session2_id);
            $this->assertSame($cookieValueBefore, $cookieValueAfter);
        } else {
            $this->cookieCheck($this->getCookieValues(), $session);

            $this->assertNotEquals($session_id, $session2_id);
            $this->assertNotEquals($cookieValueBefore, $cookieValueAfter);
        }

        $this->assertSame(2, $session->status);

        $session->destroy();
    }

    /**
     * Test create and get with property access.
     *
     * @return void
     */
    public function testCreateAndGetWithPropertyAccess(): void
    {
        self::$session->testData = 'foo';

        $this->assertSame(self::$session->testData, 'foo');
    }

    /**
     * Test delete and isset with property access.
     *
     * @return void
     */
    public function testDeleteAndIssetWithPropertyAccess(): void
    {
        unset(self::$session->testData);

        $this->assertFalse(isset(self::$session->testData));
    }

    /**
     * Test get unstored value with property access.
     *
     * @return void
     */
    public function testGetUnstoredValueWithPropertyAccess(): void
    {
        $this->assertNull(self::$session->testData);
    }

    /**
     * Test create and get with array access.
     *
     * @return void
     */
    public function testCreateAndGetWithArrayAccess(): void
    {
        self::$session['testData'] = 'foo';

        $this->assertSame(self::$session['testData'], 'foo');
    }

    /**
     * Test delete and isset with array access.
     *
     * @return void
     */
    public function testDeleteAndIssetWithArrayAccess(): void
    {
        unset(self::$session['testData']);

        $this->assertFalse(isset(self::$session['testData']));
    }

    /**
     * Test get unstored value with array access.
     *
     * @return void
     */
    public function testGetUnstoredValueWithArrayAccess(): void
    {
        $this->assertNull(self::$session['testData']);
    }

    /**
     * Test create and get with array access trait method.
     *
     * @return void
     */
    public function testCreateAndGetWithTraitMethod(): void
    {
        self::$session->offsetSet('testData', 'foo');

        $this->assertSame(self::$session->offsetGet('testData'), 'foo');
    }

    /**
     * Test delete and isset with array access trait method.
     *
     * @return void
     */
    public function testDeleteAndIssetWithTraitMethod(): void
    {
        self::$session->offsetUnset('testData');

        $this->assertFalse(self::$session->offsetExists('testData'));
    }

    /**
     * Test get unstored value with array access trait method.
     *
     * @return void
     */
    public function testGetUnstoredValueWithTraitMethod(): void
    {
        $this->assertNull(self::$session->offsetGet('testData'));
    }

    /**
     * Get session cookie set values.
     *
     * @requires extension xdebug
     *
     * @return void
     */
    public function getCookieValues(): array
    {
        $headers = \xdebug_get_headers();
        $cookie = [];

        foreach ($headers as $value) {
            if (\strstr($value, 'Set-Cookie:') !== false) {
                $cookie[] = \explode(';', \str_replace('Set-Cookie: ', "", $value));
            }
        }

        $cleanedCookie = [];

        foreach ($cookie as $values) {
            $tmpCookie = [];

            foreach ($values as $value) {
                $explode = \explode('=', \ltrim(\rtrim($value)));

                $name = \ltrim(\rtrim($explode[0]));

                $tmpCookie[$name] = (isset($explode[1])) ? $explode[1] : null;
            }

            $cleanedCookie[] = $tmpCookie;
        }

        if (\count($cleanedCookie) > 0) {
            $key = \array_key_last($cleanedCookie);

            return $cleanedCookie[$key];
        }

        return $cleanedCookie;
    }

    /**
     * Get the cookie value;
     *
     * @param array $cookieArray
     *
     * @return void
     */
    public function getCookieValue(array $cookieArray): string
    {
        $sessionName = \session_name();

        return $cookieArray[$sessionName];
    }

    /**
     * Test for cookie compliance.
     *
     * @param array   $cookie
     * @param Session $session
     *
     * @return void
     */
    public function cookieCheck(array $cookie, Session $session): void
    {
        $this->assertSame($cookie['linna_session'], $session->id);

        $cookieExpires = \strtotime($cookie['expires']);
        $resultExpires = \strtotime(\date(DATE_COOKIE, \time() + 1800));

        $timeDifference = $resultExpires - $cookieExpires;
        $timeDifference = \abs($timeDifference);

        $this->assertLessThanOrEqual(1, $timeDifference);
        $this->assertGreaterThanOrEqual(0, $timeDifference);

        $this->assertSame($cookie['Max-Age'], '1800');
        $this->assertSame($cookie['path'], '/app');
        $this->assertSame($cookie['domain'], 'https://linna.tools');
        $this->assertNull($cookie['HttpOnly']);
    }
}
