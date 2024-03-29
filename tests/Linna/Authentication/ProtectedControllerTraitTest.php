<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use Linna\Authentication\Exception\AuthenticationException;
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
    /** @var Session The session class instance. */
    protected static Session $session;

    /** @var Password The password class instance. */
    protected static Password $password;

    /** @var Authentication Authentication class instance. */
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
        //self::$session = null;
        //self::$password = null;
        //self::$authentication = null;
    }

    /**
     * Test access to protected controller with login.
     *
     * @runInSeparateProcess
     *
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
        $this->assertTrue((new ProtectedController(new class () extends Model {
        }, self::$authentication))->test);
        $this->assertTrue((new ProtectedController(new class () extends Model {
        }, self::$authentication))->action());

        $this->assertTrue(self::$authentication->logout());

        self::$session->destroy();
    }

    /**
     * Test acces to protected controller without login.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedControllerWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        $this->expectException(AuthenticationException::class);

        (new ProtectedController(new class () extends Model {
        }, self::$authentication));
    }

    /**
     * Test acces to protected controller with redirect without login.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedControllerWithRedirectWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        $this->expectException(AuthenticationException::class);

        (new ProtectedControllerWithRedirect(new class () extends Model {
        }, self::$authentication));
    }

    /**
     * Test acces to protected method without login.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedMethodWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        try {
            (new ProtectedMethodController(new class () extends Model {
            }, self::$authentication))->ProtectedAction();
        } catch (AuthenticationException $e) {
            $this->assertSame('/error', $e->getPath());
        }
    }

    /**
     * Test acces to protected method with redirect without login.
     *
     * @requires extension xdebug
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAccessProtectedMethodWithRedirectWithoutLogin(): void
    {
        $this->assertFalse(self::$authentication->isLogged());

        try {
            (new ProtectedMethodController(new class () extends Model {
            }, self::$authentication))->ProtectedActionWithRedirect();
        } catch (AuthenticationException $e) {
            $headers = \xdebug_get_headers();

            foreach ($headers as $value) {
                if (\strpos($value, 'Location:') === 0) {
                    $this->assertSame('Location: http://localhost', $value);

                    $location = \str_replace('Location: ', '', $value);
                }
            }

            $this->assertSame('/', $e->getPath());
            $this->assertSame('http://localhost', $location);
        }
    }
}
