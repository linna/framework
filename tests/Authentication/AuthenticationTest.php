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

use Linna\Authentication\Authentication;
use Linna\Authentication\Password;
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
    protected $session;

    /**
     * @var Password The password class.
     */
    protected $password;

    /**
     * @var Authentication The authentication class
     */
    protected $authentication;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $session = new Session();
        $password = new Password();

        $this->password = $password;
        $this->session = $session;
        $this->authentication = new Authentication($session, $password);
    }

    /**
     * Test login.
     *
     * @runInSeparateProcess
     */
    public function testLogin(): void
    {
        $this->session->start();

        //attemp first login
        $this->assertTrue($this->authentication->login('root', 'password', 'root', $this->password->hash('password'), 1));
        $this->assertTrue($this->session->login['login']);

        //attemp check if logged
        $this->assertTrue($this->authentication->isLogged());

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $this->assertTrue((new Authentication($this->session, $this->password))->isNotLogged());

        $this->session->destroy();
    }

    /**
     * Test login data.
     *
     * @runInSeparateProcess
     */
    public function testLoginData(): void
    {
        $this->session->start();

        //attemp first login
        $this->assertTrue($this->authentication->login('root', 'password', 'root', $this->password->hash('password'), 1));
        $this->assertTrue($this->session->login['login']);

        //attemp check if logged
        $this->assertTrue($this->authentication->isLogged());

        //check login data
        $this->assertTrue($this->authentication->getLoginData()['login']);
        $this->assertEquals(1, $this->authentication->getLoginData()['user_id']);
        $this->assertEquals('root', $this->authentication->getLoginData()['user_name']);

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $this->assertTrue((new Authentication($this->session, $this->password))->isNotLogged());

        $this->session->destroy();
    }

    /**
     * Test logout.
     *
     * @runInSeparateProcess
     */
    public function testLogout(): void
    {
        $this->session->start();

        //attemp first login
        $this->assertTrue($this->authentication->login('root', 'password', 'root', $this->password->hash('password'), 1));
        $this->assertTrue($this->session->login['login']);

        //attemp check if logged
        $this->assertTrue($this->authentication->isLogged());

        //do logout
        $this->assertTrue($this->authentication->logout());

        //re check if logged
        $this->assertFalse($this->authentication->isLogged());

        //create new login instance
        $this->assertTrue((new Authentication($this->session, $this->password))->isNotLogged());

        $this->session->destroy();
    }

    /**
     * Test incorrect login.
     *
     * @runInSeparateProcess
     */
    public function testIncorrectLogin(): void
    {
        //try login with bad credentials
        $this->assertFalse($this->authentication->login('root', 'badPassword', 'root', $this->password->hash('password'), 1));
        $this->assertFalse($this->authentication->login('badUser', 'password', 'root', $this->password->hash('password'), 1));
    }

    /**
     * Test login refresh.
     *
     * @runInSeparateProcess
     */
    public function testLoginRefresh(): void
    {
        $this->session->start();

        //attemp first login
        $this->assertTrue($this->authentication->login('root', 'password', 'root', $this->password->hash('password'), 1));
        $this->assertTrue($this->session->login['login']);

        //attemp check if logged
        $this->assertTrue($this->authentication->isLogged());

        $this->session->commit();

        $this->session->start();

        $this->assertTrue($this->session->login['login']);

        //create second instance and attemp check if logged
        $this->assertTrue((new Authentication($this->session, $this->password))->isLogged());

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $this->assertTrue((new Authentication($this->session, $this->password))->isNotLogged());

        $this->session->destroy();
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
     */
    public function testLoginRefreshTime(int $time, bool $loginPass): void
    {
        $this->session->start();

        //attemp first login
        $this->assertTrue($this->authentication->login('root', 'password', 'root', $this->password->hash('password'), 1));
        $this->assertTrue($this->session->login['login']);

        //create second instance and attemp check if logged
        $this->assertTrue((new Authentication($this->session, $this->password))->isLogged());

        //simulate expired login
        $this->session->loginTime = time() - $time;

        //attemp second login
        $this->assertEquals($loginPass, (new Authentication($this->session, $this->password))->isNotLogged());
        $this->assertEquals(!$loginPass, (new Authentication($this->session, $this->password))->isLogged());

        $this->session->destroy();
    }
}
