<?php

/**
 * Leviu.
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
namespace Leviu\Database;

/**
 * Singleton Pattern for PDO Database connection
 * https://it.wikipedia.org/wiki/Singleton.
 * http://php.net/manual/en/class.pdo.php
 */
class Database extends \PDO
{
    /**
     * @static object $instance The DB istance
     */
    private static $instance;

    /**
     * Constructor
     * 
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
            parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            echo 'Error!: '.$e->getMessage();
            die();
        }
    }

    /**
     * Forbids the object clone
     * 
     * @since 0.1.0
     */
    private function __clone()
    {
        //It forbids the object clone
    }

    /**
     * Return te instance of database connection
     * 
     * @return object
     *
     * @since 0.1.0
     */
    public static function connect()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
