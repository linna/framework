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
 * Mysql Improved Extension Adapter.
 */
class MysqliAdapter implements StorageInterface
{
    /**
     * @var string Mysql host es. 127.0.0.1
     */
    protected $host;

    /**
     * @var string Username for data base connection
     */
    protected $user;

    /**
     * @var string Password for data base connection
     */
    protected $password;

    /**
     * @var string Database name
     */
    protected $database;

    /**
     * @var int Mysql tcp port es. 3306
     */
    protected $port;

    /**
     * Constructor.
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param int    $port
     */
    public function __construct(string $host, string $user, string $password, string $database, int $port)
    {
        $this->host = $host;

        $this->user = $user;

        $this->password = $password;

        $this->database = $database;

        $this->port = $port;
    }

    /**
     * Get Resource.
     *
     * @return \mysqli
     */
    public function getResource()
    {
        mysqli_report(MYSQLI_REPORT_ALL);

        return new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
    }
}
