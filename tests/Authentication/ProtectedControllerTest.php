<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Authentication\Authenticate;
use Linna\Authentication\Password;
use Linna\TestHelper\Mvc\MultipleModel;
use Linna\TestHelper\Mvc\MultipleProtectedController;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Protected Controller Test.
 */
class ProtectedControllerTest extends TestCase
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
     * @var Authenticate Authenticate Instance.
     */
    protected $authenticate;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $session = new Session();
        $password = new Password();

        $this->session = $session;
        $this->password = $password;

        $this->authenticate = new Authenticate($session, $password);
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

        $this->assertTrue($this->authenticate->login(
            'root',
            'password',
            'root',
            $this->password->hash('password'),
            1
        ));

        $this->assertTrue($this->authenticate->isLogged());
        $this->assertTrue((new MultipleProtectedController(new MultipleModel(), $this->authenticate))->test);
        $this->assertTrue((new MultipleProtectedController(new MultipleModel(), $this->authenticate))->ProtectedAction());

        $this->assertTrue($this->authenticate->logout());

        $this->session->destroy();
    }

    /**
     * Test acces to protected controller without login.
     *
     * @requires extension xdebug
     * @runInSeparateProcess
     * @outputBuffering disabled
     */
    public function testAccessProtectedControllerWithoutLogin(): void
    {
        ob_start();

        (new MultipleProtectedController(new MultipleModel(), $this->authenticate));
        $headers_list = xdebug_get_headers();

        ob_end_clean();

        $this->assertFalse($this->authenticate->isLogged());
        $this->assertTrue(in_array('Location: http://localhost', $headers_list));
    }
}
