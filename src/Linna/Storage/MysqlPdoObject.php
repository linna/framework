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
 * Mysql PDO Adapter.
 */
class MysqlPdoObject implements StorageObjectInterface
{
    /**
     * @var string Dsn string for mysql
     */
    protected $dsn;

    /**
     * @var string Username for data base connection
     */
    protected $user;

    /**
     * @var string Password for data base connection
     */
    protected $password;

    /**
     * @var array PDO options
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @param array  $options
     */
    public function __construct(string $dsn, string $user, string $password, array $options)
    {
        $this->dsn = $dsn;

        $this->user = $user;

        $this->password = $password;

        $this->options = $options;
    }

    /**
     * Get Resource.
     *
     * @return PDO
     */
    public function getResource()
    {
        return new PDO($this->dsn, $this->user, $this->password, $this->options);
    }
}
