<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Auth\Authenticate;
use Linna\Auth\Password;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class AuthenticateTest extends TestCase
{
    protected $session;
    protected $password;
    protected $authenticate;

    public function setUp()
    {
        $session = new Session();
        $password = new Password();

        $this->authenticate = new Authenticate($session, $password);
        $this->password = $password;
        $this->session = $session;
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogin()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //attemp first login
        $loginResult = $this->authenticate->login('root', 'password', $storedUser, $storedPassword, 1);

        //attemp check if logged
        $logged = $this->authenticate->logged;

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $secondLogin = new Authenticate($this->session, $this->password);
        $notLogged = $secondLogin->logged;

        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $logged);
        $this->assertEquals(false, $notLogged);

        $this->session->destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogout()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //attemp first login
        $this->authenticate->login('root', 'password', $storedUser, $storedPassword, 1);
        $loginResult = $this->authenticate->logged;

        //do logout
        $this->authenticate->logout();

        //create new login instance
        $login = new Authenticate($this->session, $this->password);
        $noLoginResult = $login->logged;

        $this->assertEquals(true, $loginResult);
        $this->assertEquals(false, $noLoginResult);

        $this->session->destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testIncorrectLogin()
    {
        $this->session->start();

         //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //try login with bad credentials
        $loginResult = $this->authenticate->login('root', 'badPassword', $storedUser, $storedPassword, 1);
        $loginResult2 = $this->authenticate->login('badUser', 'password', $storedUser, $storedPassword, 1);

        $this->assertEquals(false, $loginResult);
        $this->assertEquals(false, $loginResult2);

        $this->session->destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoginRefresh()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //attemp first login
        $login = new Authenticate($this->session, $this->password);
        $firstLogin = $login->login('root', 'password', $storedUser, $storedPassword, 1);
        //attemp check if logged
        $firstLogged = $login->logged;

        $this->session->commit();

        $this->session->start();

        //create second instance
        $login = new Authenticate($this->session, $this->password);
        //attemp check if logged
        $secondLogged = $login->logged;

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $secondLogin = new Authenticate($this->session, $this->password);
        $notLogged = $secondLogin->logged;

        $this->assertEquals(true, $firstLogin);
        $this->assertEquals(true, $firstLogged);
        $this->assertEquals(true, $secondLogged);
        $this->assertEquals(false, $notLogged);

        $this->session->destroy();
    }
}
