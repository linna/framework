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
 * MongoDB.
 */
class MongoDbStorage implements StorageInterface
{
    /**
     * @var array MongoDB connection options
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
     * @return Client
     */
    public function getResource()
    {
        return new Client(
            $this->options['uri'],
            $this->options['uriOptions'],
            $this->options['driverOptions']
        );
    }
}
