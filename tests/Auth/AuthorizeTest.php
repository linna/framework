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
use Linna\Auth\Authorize;
use Linna\Auth\Password;
use Linna\Foo\Mappers\PermissionMapper;
use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

class AuthorizeTest extends TestCase
{
    protected $session;
    protected $password;
    protected $authenticate;
    protected $permissionMapper;

    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];

        $pdo = (new StorageFactory('mysqlpdo', $options))->getConnection();

        $session = new Session();
        $password = new Password();

        $this->authenticate = new Authenticate($session, $password);
        $this->password = $password;
        $this->session = $session;

        $this->permissionMapper = new PermissionMapper($pdo);
    }

    public function testCreateAuthorize()
    {
        $authorize = new Authorize($this->authenticate, $this->permissionMapper);

        $this->assertInstanceOf(Authorize::class, $authorize);
    }

    public function testCanWithoutLogin()
    {
        $authorize = new Authorize($this->authenticate, $this->permissionMapper);

        $this->assertInstanceOf(Authorize::class, $authorize);
        $this->assertEquals(false, $authorize->can('see users'));
    }

    public function testCanWithNotExistentPermission()
    {
        $authorize = new Authorize($this->authenticate, $this->permissionMapper);

        $this->assertInstanceOf(Authorize::class, $authorize);
        $this->assertEquals(false, $authorize->can('Not Existent Permission'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCanWithLogin()
    {
        $this->session->start();

        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];

        //create new pdo here because run in separate process try to serialize it
        //and return error
        $pdo = (new StorageFactory('mysqlpdo', $options))->getConnection();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        $authenticate = new Authenticate($this->session, $this->password);

        //attemp login
        $authenticate->login('root', 'password', $storedUser, $storedPassword, 1);

        //pass as first argument new instance because phpunit try to serialize pdo.????? booo
        $authorize = new Authorize(new Authenticate($this->session, $this->password), new PermissionMapper($pdo));

        $this->assertEquals(true, $authenticate->logged);
        $this->assertEquals(true, $authorize->can('see users'));
        $this->assertEquals(false, $authorize->can('Not Existent Permission'));

        $this->session->destroy();
    }
}
