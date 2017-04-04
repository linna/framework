<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
use Linna\Auth\Authenticate;
use Linna\Auth\Password;
use Linna\Autoloader;
use Linna\Foo\Mvc\FooModel;
use Linna\Foo\Mvc\FooProtectedController;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class ProtectedControllerTest extends TestCase
{
    public $autoloader;
    public $session;
    public $password;

    public function setUp()
    {
        $autoloader = new Autoloader();
        $autoloader->register();
        $autoloader->addNamespaces([
           ['Linna\Foo', dirname(__DIR__).'/FooClass'],
        ]);

        $this->autoloader = $autoloader;

        $session = new Session();
        $password = new Password();

        $this->session = $session;
        $this->password = $password;

        $this->authenticate = new Authenticate($session, $password);
    }

    /**
     * @runInSeparateProcess
     * @outputBuffering disabled
     */
    public function testAccessProtectedControllerWithLogin()
    {
        $this->session->start();

        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        $this->authenticate->login('root', 'password', $storedUser, $storedPassword, 1);

        $model = new FooModel();

        $controller = new FOOProtectedController($model, $this->authenticate);

        $this->assertEquals(true, $this->authenticate->logged);
        $this->assertEquals(true, $controller->test);

        $this->authenticate->logout();

        $this->session->destroy();
    }

    /**
     * @runInSeparateProcess
     * @outputBuffering disabled
     */
    public function testAccessProtectedControllerWithoutLogin()
    {
        if (!function_exists('xdebug_get_headers')) {
            $this->markTestSkipped('Xdebug not installed');
        }

        $model = new FooModel();

        ob_start();

        (new FooProtectedController($model, $this->authenticate));
        $headers_list = xdebug_get_headers();

        ob_end_clean();

        $this->assertEquals(false, $this->authenticate->logged);
        $this->assertEquals(true, in_array('Location: http://localhost', $headers_list));
    }
}
