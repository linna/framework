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
class ProtectedControllerTest extends TestCase
{
    /**
     * @var Session The session class instance.
     */
    protected $session;

    /**
     * @var Password The password class instance.
     */
    protected $password;

    /**
     * @var Authentication Authentication class instance.
     */
    protected $authentication;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $session = new Session();
        $password = new Password();

        $this->session = $session;
        $this->password = $password;

        $this->authentication = new Authentication($session, $password);
    }

    /**
     * Tear Down.
     */
    public function tearDown(): void
    {
        unset($this->session, $this->password, $this->authentication);
    }

    /**
     * Test access to protected controller with login.
     *
     * @runInSeparateProcess
     * @outputBuffering disabled
     */
    public function testAccessProtectedControllerWithLogin(): void
    {
        $this->session->start();

        $this->assertTrue($this->authentication->login(
            'root',
            'password',
            'root',
            $this->password->hash('password'),
            1
        ));

        $this->assertTrue($this->authentication->isLogged());
        $this->assertTrue((new ProtectedController(new Model(), $this->authentication))->test);
        $this->assertTrue((new ProtectedController(new Model(), $this->authentication))->action());

        $this->assertTrue($this->authentication->logout());

        $this->session->destroy();
    }

    /**
     * Test acces to protected controller without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     */
    public function testAccessProtectedControllerWithoutLogin(): void
    {
        $this->assertFalse($this->authentication->isLogged());

        (new ProtectedController(new Model(), $this->authentication));
    }

    /**
     * Test acces to protected controller with redirect without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     */
    public function testAccessProtectedControllerWithRedirectWithoutLogin(): void
    {
        $this->assertFalse($this->authentication->isLogged());

        (new ProtectedControllerWithRedirect(new Model(), $this->authentication));
    }

    /**
     * Test acces to protected method without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     */
    public function testAccessProtectedMethodWithoutLogin(): void
    {
        $this->assertFalse($this->authentication->isLogged());

        (new ProtectedMethodController(new Model(), $this->authentication))->ProtectedAction();
    }

    /**
     * Test acces to protected method with redirect without login.
     *
     * @expectedException Linna\Authentication\Exception\AuthenticationException
     * @runInSeparateProcess
     */
    public function testAccessProtectedMethodWithRedirectWithoutLogin(): void
    {
        $this->assertFalse($this->authentication->isLogged());

        (new ProtectedMethodController(new Model(), $this->authentication))->ProtectedActionWithRedirect();
    }
}
