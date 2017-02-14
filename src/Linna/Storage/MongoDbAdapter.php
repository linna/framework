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

use MongoDB\Client;

/**
 * MongoDB Adapter.
 */
class MongoDbAdapter implements AdapterInterface
{
    /**
     * @var string String for MongoDB connection
     */
    protected $uri;

    /**
     * @var array Specifies additional URI options
     */
    protected $uriOptions;

    /**
     * @var array Specify driver-specific options
     */
    protected $driverOptions;

    /**
     * Constructor.
     *
     * @param string $uri
     * @param array  $uriOptions
     * @param array  $driverOptions
     */
    public function __construct(string $uri = 'mongodb://127.0.0.1/', array $uriOptions = [], array $driverOptions = [])
    {
        $this->uri = $uri;
        $this->uriOptions = $uriOptions;
        $this->driverOptions = $driverOptions;
    }

    /**
     * Get Resource.
     *
     * @return \MongoDB\Client
     */
    public function getResource()
    {
        return new Client($this->uri, $this->uriOptions, $this->driverOptions);
    }
}
