<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\core;

/**
 * Databese
 * - Singleton Pattern for Database connection
 * https://it.wikipedia.org/wiki/Singleton
 */
class Database extends \PDO
{
    /**
     * @var object $instance
     * @static object $instance The DB istance
     */
    private static $instance;

    /**
     * Database constructor
     * 
     * @since 0.1.0
     */
    public function __construct()
    {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING);

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        try {
            parent::__construct(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            echo "Error!: " . $e->getMessage();
            die();
        }
    }

    /**
     * __clone
     * 
     * Forbids the object clone
     * 
     * @since 0.1.0
     */
    private function __clone()
    {
        //It forbids the object clone
    }

    /**
     * connect
     * 
     * Return te instance of database connection
     * 
     * @return object
     * @since 0.1.0
     */
    public static function connect()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
}
