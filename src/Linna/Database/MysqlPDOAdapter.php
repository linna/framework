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

class MysqlPDOAdapter implements AdapterInterface
{
    protected $dsn;
    
    protected $user;
            
    protected $password;
    
    protected $options;
    
    public function __construct(string $dsn, string $user, string $password, array $options)
    {
        $this->dsn = $dsn;
    
        $this->user = $user;
        
        $this->password = $password;
        
        $this->options = $options;
    }
    
    public function getResource()
    {
        try {
            return new \PDO($this->dsn, $this->user, $this->password, $this->options);
        } catch (\PDOException $exception) {
            echo 'PDOException: '.$exception->getMessage();
        }
    }
}
