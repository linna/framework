<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2022, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\TestHelper\Pdo;

use PDO;

class PdoOptionsFactory
{
    private static function parseComposerJson(): array
    {
        $path = \substr(__DIR__, 0, \strpos(__DIR__, 'tests/Linna/TestHelper/Pdo'));
        $path .= 'composer.json';

        return \json_decode(\file_get_contents($path), true);
    }

    private static function isDatabase(string $which): bool
    {
        $json = self::parseComposerJson();

        return isset($json['require-dev'][$which]);
    }

    public static function getOptions(): array
    {
        if (self::isDatabase('linna/auth-mapper-mysql')) {
            return [
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

        if (self::isDatabase('linna/auth-mapper-pgsql')) {
            return [
                'dsn'      => $GLOBALS['pdo_pgsql_dsn'],
                'user'     => $GLOBALS['pdo_pgsql_user'],
                'password' => $GLOBALS['pdo_pgsql_password'],
                'options'  => [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT         => false,
                ],
            ];
        }
    }
}
