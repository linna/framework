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

use MongoDB\Client;
use MongoDB\Exception\ConnectionException;

/**
 * MongoDB Adapter
 *
 */
class MongoDbAdapter implements AdapterInterface
{
    /**
     * @var string $serverString String for MongoDB connection
     */
    protected $serverString;
    
    /**
     * Constructor
     *
     * @param string $serverString
     */
    public function __construct(string $serverString)
    {
        $this->serverString = $serverString;
    }
    
    /**
     * Get Resource
     *
     * @return \MongoDB\Client
     */
    public function getResource()
    {
        try {
            return new Client($this->serverString);
        } catch (ConnectionException $exception) {
            echo 'MongoDB Connection Fail: '.$exception->getMessage();
            return null;
        }
    }
}
