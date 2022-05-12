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

//use Linna\Authentication\Authentication;
//use Linna\Authentication\Password;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Authentication Test.
 */
class AuthenticationTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected static Session $session;

    /**
     * @var Password The password class.
     */
    protected static Password $password;

    /**
     * @var Authentication The authentication class
     */
    protected static Authentication $authentication;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $session = new Session();
        $password = new Password();

        self::$password = $password;
        self::$session = $session;
        self::$authentication = new Authentication($session, $password);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$authentication = null;
        //self::$password = null;
        //self::$session = null;
    }

    /**
     * Test login.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testLogin(): void
    {
        self::$session->start();

        $sessionId = self::$session->id;

        //attemp first login
        $this->assertTrue(self::$authentication->login('root', 'password', 'root', self::$password->hash('password'), 1));
        $this->assertTrue(self::$session->login['login']);

        //attemp check if logged
        $this->assertTrue(self::$authentication->isLogged());

        //check session id regeneration
        $this->assertNotSame($sessionId, self::$session->id);

        //simulate expired login
        self::$session->loginTime = \time() - 3600;

        //attemp check if logged
        $this->assertTrue((new Authentication(self::$session, self::$password))->isNotLogged());

        self::$session->destroy();
    }

    /**
     * Test login data.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testLoginData(): void
    {
        self::$session->start();

        $sessionId = self::$session->id;

        //attemp first login
        $this->assertTrue(self::$authentication->login('root', 'password', 'root', self::$password->hash('password'), 1));
        $this->assertTrue(self::$session->login['login']);

        //attemp check if logged
        $this->assertTrue(self::$authentication->isLogged());

        //check session id regeneration
        $this->assertNotSame($sessionId, self::$session->id);

        //check login data
        $this->assertTrue(self::$authentication->getLoginData()['login']);
        $this->assertEquals(1, self::$authentication->getLoginData()['user_id']);
        $this->assertEquals('root', self::$authentication->getLoginData()['user_name']);

        //simulate expired login
        self::$session->loginTime = \time() - 3600;

        //attemp check if logged
        $this->assertTrue((new Authentication(self::$session, self::$password))->isNotLogged());

        self::$session->destroy();
    }

    /**
     * Test logout.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testLogout(): void
    {
        self::$session->start();

        //attemp first login
        $this->assertTrue(self::$authentication->login('root', 'password', 'root', self::$password->hash('password'), 1));
        $this->assertTrue(self::$session->login['login']);

        //attemp check if logged
        $this->assertTrue(self::$authentication->isLogged());

        $sessionId = self::$session->id;

        //do logout
        $this->assertTrue(self::$authentication->logout());

        //re check if logged
        $this->assertFalse(self::$authentication->isLogged());

        //check session id regeneration
        $this->assertNotSame($sessionId, self::$session->id);

        //create new login instance
        $this->assertTrue((new Authentication(self::$session, self::$password))->isNotLogged());

        self::$session->destroy();
    }

    /**
     * Test incorrect login.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testIncorrectLogin(): void
    {
        //try login with bad credentials
        $this->assertFalse(self::$authentication->login('root', 'badPassword', 'root', self::$password->hash('password'), 1));
        $this->assertFalse(self::$authentication->login('badUser', 'password', 'root', self::$password->hash('password'), 1));
    }

    /**
     * Test login refresh.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testLoginRefresh(): void
    {
        self::$session->start();

        //attemp first login
        $this->assertTrue(self::$authentication->login('root', 'password', 'root', self::$password->hash('password'), 1));
        $this->assertTrue(self::$session->login['login']);

        //attemp check if logged
        $this->assertTrue(self::$authentication->isLogged());

        self::$session->commit();

        self::$session->start();

        $this->assertTrue(self::$session->login['login']);

        //create second instance and attemp check if logged
        $this->assertTrue((new Authentication(self::$session, self::$password))->isLogged());

        //simulate expired login
        self::$session->loginTime = \time() - 3600;

        //attemp second login
        $this->assertTrue((new Authentication(self::$session, self::$password))->isNotLogged());

        self::$session->destroy();
    }

    /**
     * Login time provider.
     *
     * @return array
     */
    public function loginTimeProvider(): array
    {
        return [
            [1798, false],
            [1799, false],
            [1800, false],
            [1801, true],
            [1802, true],
            [1803, true]
        ];
    }

    /**
     * Test login refresh.
     *
     * @dataProvider loginTimeProvider
     * @runInSeparateProcess
     *
     * @param int  $time
     * @param bool $loginPass
     *
     * @return void
     */
    public function testLoginRefreshTime(int $time, bool $loginPass): void
    {
        self::$session->start();

        //attemp first login
        $this->assertTrue(self::$authentication->login('root', 'password', 'root', self::$password->hash('password'), 1));
        $this->assertTrue(self::$session->login['login']);

        //create second instance and attemp check if logged
        $this->assertTrue((new Authentication(self::$session, self::$password))->isLogged());

        //simulate expired login
        self::$session->loginTime = \time() - $time;

        //attemp second login
        $this->assertEquals($loginPass, (new Authentication(self::$session, self::$password))->isNotLogged());
        $this->assertEquals(!$loginPass, (new Authentication(self::$session, self::$password))->isLogged());

        self::$session->destroy();
    }
}
