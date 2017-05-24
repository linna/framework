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
     * Constructor.
     *
     * @param string $dsn
     * @param string $user
     * @param string $password
     */
    public function __construct(string $dsn, string $user, string $password)
    {
        $this->dsn = $dsn;

        $this->user = $user;

        $this->password = $password;
    }

    /**
     * Get Resource.
     *
     * @return PDO
     */
    public function getResource()
    {
        return new PDO($this->dsn, $this->user, $this->password);
    }
}
