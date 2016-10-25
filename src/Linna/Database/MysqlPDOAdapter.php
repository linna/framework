<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Database;

/**
 * Mysql PDO Adapter
 * 
 */
class MysqlPDOAdapter implements AdapterInterface
{
    /**
     * @var string $dns Dsn string for mysql 
     */
    protected $dsn;
    
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
    protected $options;
    
    /**
     * Constructor
     * 
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @param array $options
     */
    public function __construct(string $dsn, string $user, string $password, array $options)
    {
        $this->dsn = $dsn;
    
        $this->user = $user;
        
        $this->password = $password;
        
        $this->options = $options;
    }
    
    /**
     * Get Resource
     * 
     * @return \PDO
     */
    public function getResource()
    {
        try {
            return new \PDO($this->dsn, $this->user, $this->password, $this->options);
        } catch (\PDOException $exception) {
            echo 'Connection Fail: '.$exception->getMessage();
            return null;
        }
    }
}
