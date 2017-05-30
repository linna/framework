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

/**
 * Storage Interface.
 */
interface StorageInterface
{
    /**
     * Constructor.
     * 
     * @param array $options Connection options
     */
    public function __construct(array $options);

    /**
     * Return resource to Database class.
     */
    public function getResource();
}
