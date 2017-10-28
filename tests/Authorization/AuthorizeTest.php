<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Authentication\Authenticate;
use Linna\Authentication\Password;
use Linna\Authorization\Authorize;
use Linna\TestHelper\Mappers\PermissionMapper;
use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

/**
 * Authorize Test.
 */
class AuthorizeTest extends TestCase
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
     * @var Authenticate The authenticate class
     */
    protected $authenticate;
    
    /**
     * @var PermissionMapper The permission mapper
     */
    protected $permissionMapper;

    /**
     * Setup.
     */
    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $session = new Session();
        $password = new Password();
        $authenticate = new Authenticate($session, $password);
        $permissionMapper = new PermissionMapper((new StorageFactory('pdo', $options))->get());
        
        
        $this->password = $password;
        $this->session = $session;
        $this->authenticate = $authenticate;
        $this->permissionMapper = $permissionMapper;
    
        $this->authorize = new Authorize($authenticate, $permissionMapper);
    }
    
    /**
     * Test create new authorize instance.
     */
    public function testNewAuthorizeInstance()
    {
        $this->assertInstanceOf(Authorize::class, $this->authorize);
    }

    /**
     * Test can do an action without login.
     */
    public function testCanDoActionWithoutLogin()
    {
        $this->assertFalse($this->authorize->can('see users'));
    }

    /**
     * Test can do an action with not existent permission.
     */
    public function testCanDoActionWithNotExistentPermission()
    {
        $this->assertFalse($this->authorize->can('Not Existent Permission'));
    }

    /**
     * Test can do an action with login.
     *
     * @runInSeparateProcess
     */
    public function testCanDoActionWithLogin()
    {
        $this->session->start();

        $authenticate = new Authenticate($this->session, $this->password);

        //attemp login
        $this->assertTrue($authenticate->login(
            'root',
            'password',
            'root',
            $this->password->hash('password'),
            1
        ));

        //pass as first argument new instance because phpunit try to serialize pdo.????? I don't know where.
        $authorize = new Authorize(new Authenticate($this->session, $this->password), $this->permissionMapper);

        $this->assertTrue($authenticate->isLogged());
        $this->assertTrue($authorize->can('see users'));
        $this->assertFalse($authorize->can('Not Existent Permission'));

        $this->session->destroy();
    }
}
