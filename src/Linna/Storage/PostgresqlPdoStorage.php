<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

use PDO;

/**
 * Postgresql PDO.
 */
class PostgresqlPdoStorage implements StorageInterface
{
    /**
     * @var array PDO options
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array  $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    
    /**
     * Get Resource.
     *
     * @return PDO
     */
    public function getResource()
    {
        return new PDO(
            $this->options['dsn'], 
            $this->options['user'], 
            $this->options['password'], 
            $this->options['options']
        );
    }
}
