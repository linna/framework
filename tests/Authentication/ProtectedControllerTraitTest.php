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
use Linna\Mvc\Model;
use Linna\TestHelper\Mvc\ProtectedController;
use Linna\TestHelper\Mvc\ProtectedControllerWithRedirect;
use Linna\TestHelper\Mvc\ProtectedMethodController;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Protected Controller Test.
 */
class ProtectedControllerTraitTest extends TestCase
{
    /**
     * @var Session The session class instance.
     */
    protected static $session;

    /**
     * @var Password The password class instance.
     */
    protected static $password;

    /**
     * @var Authentication Authentication class instance.
     */
    protected static $authentication;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $session = new Session();
        $password = new Password();

        self::$session = $session;
        self::$password = $password;
        self::$authentication = new Authentication($session, $password);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$session = null;
        self::$password = null;
        self::$authentication = null;
    }

    /**
     * Test access to protected controller with login.
     *
     * @runInSeparateProcess
     * @outputBuffering disabled
     *
     * @return void
     */
    public function testAccessProtectedControllerWithLogin(): void
    {
        self::$session->start();

        $this->assertTrue(self::$authentication->login(
            'root',
            'password',
            'root',
            self::$password->hash('password'),
            1
        ));

        $this->assertTrue(self::$authentication->isLogged());
        $this->assertTrue((new ProtectedController(new Model(), self::$authentication))->test);
        $this->assertTrue((new ProtectedController(new Model(), self::$authentication))->action());

        $this->assertTrue(self::$authentication->logout());

        self::$session->destroy();
    }

    /**
     * Test acces to protected controller without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedControllerWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        (new ProtectedController(new Model(), self::$authentication));
    }

    /**
     * Test acces to protected controller with redirect without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedControllerWithRedirectWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        (new ProtectedControllerWithRedirect(new Model(), self::$authentication));
    }

    /**
     * Test acces to protected method without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedMethodWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        (new ProtectedMethodController(new Model(), self::$authentication))->ProtectedAction();
    }

    /**
     * Test acces to protected method with redirect without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedMethodWithRedirectWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        (new ProtectedMethodController(new Model(), self::$authentication))->ProtectedActionWithRedirect();
    }
}
