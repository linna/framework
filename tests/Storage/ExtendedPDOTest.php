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

use InvalidArgumentException;
use Linna\Storage\Connectors\PdoConnector;
use PDO;
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
    public function setUp(): void
    {
        $this->options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];
    }

    /**
     * Correct parameters provider.
     *
     * @return array
     */
    public function correctParametersProvider(): array
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
     * Test query with parameters.
     *
     * @dataProvider correctParametersProvider
     */
    public function testQueryWithParameters(string $query, array $param): void
    {
        $user = (new PdoConnector($this->options))
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
     * @expectedExceptionMessage Parameter name will be in the form :name.
     */
    public function testQueryWithParameterWithWrongParameterName(): void
    {
        (new PdoConnector($this->options))
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
     * @expectedExceptionMessage Parameters array must contain at least two elements with this form: [':name', 'value'].
     */
    public function testQueryWithParameterWithTooManyParameters(): void
    {
        (new PdoConnector($this->options))
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
    public function testQueryWithParameterWithoutParameters(): void
    {
        (new PdoConnector($this->options))
            ->getResource()
            ->queryWithParam(
                'SELECT user_id, name, email FROM user WHERE name = :name',
                []
            )->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Test query status.
     *
     * @dataProvider correctParametersProvider
     */
    public function testQueryStatus(string $query, array $param): void
    {
        $pdo = (new PdoConnector($this->options))->getResource();

        $user = $pdo->queryWithParam(
            $query,
            $param
        )->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEquals(1, count($user));
        $this->assertTrue($pdo->getLastOperationStatus());
    }
}
