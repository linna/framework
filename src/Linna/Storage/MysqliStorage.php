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

use mysqli;

/**
 * Mysql Improved Extension.
 */
class MysqliStorage implements StorageInterface
{
    /**
     * @var array Mysqli connection options
     */
    protected $options;
    
    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    
    /**
     * Get Resource.
     *
     * @return mysqli
     */
    public function getResource()
    {
        mysqli_report(MYSQLI_REPORT_ALL);

        return new mysqli(
            $this->options['host'], 
            $this->options['user'], 
            $this->options['password'], 
            $this->options['database'], 
            $this->options['port']
        );
    }
}
