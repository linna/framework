<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Storage;

use mysqli;
use mysqli_sql_exception;

/**
 * Mysql Improved Extension Adapter
 *
 */
class MysqliAdapter implements AdapterInterface
{
    /**
     * @var string $dns Dsn string for mysql
     */
    protected $host;
    
    /**
     * @var string $user Username for data base connection
     */
    protected $user;
    
    /**
     * @var string $password Password for data base connection
     */
    protected $password;
    
    /**
     *
     * @var array $options PDO options
     */
    protected $database;
    
    /**
     *
     * @var array $options PDO options
     */
    protected $port;
    
    /**
     * Constructor
     * 
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param int $port
     */
    public function __construct(string $host, string $user, string $password, string $database,  int $port)
    {
        $this->host = $host;
    
        $this->user = $user;
        
        $this->password = $password;
        
        $this->database = $database;
        
        $this->port = $port;
    }
    
    /**
     * Get Resource
     *
     * @return \mysqli
     */
    public function getResource()
    {
        try {
            return new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
        } catch (mysqli_sql_exception $exception) {
            echo 'Connection Fail: '.$exception->getMessage();
            return null;
        }
    }
}
