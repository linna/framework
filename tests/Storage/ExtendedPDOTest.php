<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\PdoStorage;
use PHPUnit\Framework\TestCase;

/**
 * ExtendedPDO Test
 */
class ExtendedPDOTest extends TestCase
{
    /**
     * @var array Connection options.
     */
    protected $options = [];
    
    /**
     * Setup.
     */
    public function setUp()
    {
        $this->options = [
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
    }
    
    /**
     * Correct parameters provider
     * @return array
     */
    public function correctParametersProvider() : array
    {
        return [
            ['SELECT user_id, name, email FROM user WHERE name = :name', [
                    [':name', 'root', PDO::PARAM_STR]
                ]
            ],
            ['SELECT user_id, name, email FROM user WHERE name = :name AND user_id = :id', [
                    [':name', 'root', PDO::PARAM_STR], 
                    [':id', 1, PDO::PARAM_INT]
                ]
            ],
            ['SELECT user_id, name, email FROM user WHERE name = :name', [
                    [':name', 'root']
                ]
            ]
        ];
    }
    
    /**
     * Test query with parameter.
     * 
     * @dataProvider correctParametersProvider
     */
    public function testQueryWithParameters(string $query, array $param)
    {
        $user = (new PdoStorage($this->options))
            ->getResource()
            ->queryWithParam(
                $query,
                $param
            )->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEquals(1, count($user));
    }
    
    /**
     * Test query with parameter with wrong parameter name.
     *
     * @expectedException InvalidArgumentException
     */
    public function testQueryWithParameterWithWrongParameterName()
    {
        $user = (new PdoStorage($this->options))
            ->getResource()
            ->queryWithParam(
                'SELECT user_id, name, email FROM user WHERE name = :name',
                [['name', 'root', PDO::PARAM_STR]]
            )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Test query with parameter with too many parameters.
     *
     * @expectedException InvalidArgumentException
     */
    public function testQueryWithParameterWithTooManyParameters()
    {
        $user = (new PdoStorage($this->options))
            ->getResource()
            ->queryWithParam(
                'SELECT user_id, name, email FROM user WHERE name = :name',
                [[':name']]
            )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Test query with parameter with too many parameters.
     *
     * @expectedException PDOException
     */
    public function testQueryWithParameterWithoutParameters()
    {
        $user = (new PdoStorage($this->options))
            ->getResource()
            ->queryWithParam(
                'SELECT user_id, name, email FROM user WHERE name = :name',
                []
            )->fetchAll(PDO::FETCH_ASSOC);
    }
}
