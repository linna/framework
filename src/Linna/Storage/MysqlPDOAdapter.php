<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Storage;

class MysqlPDOAdapter implements AdapterInterface
{
    protected $dsn;
    
    protected $user;
            
    protected $password;
    
    protected $options;
    
    public function __construct($dsn, $user, $password, $options)
    {
        $this->dsn = $dsn;
    
        $this->user = $user;
        
        $this->password = $password;
        
        $this->options = $options;//array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING);
    }
    
    public function connect()
    {
        try {
            return new \PDO($this->dsn, $this->user, $this->password, $this->options);
        } catch (\PDOException $exception) {
            echo 'Error!: '.$exception->getMessage();
        }
    }
}
