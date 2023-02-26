<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage;

/**
 * Storage Interface.
 */
interface ConnectorInterface
{
    /**
     * Class Constructor.
     *
     * @param array<mixed> $options Connection options.
     */
    public function __construct(array $options);

    /**
     * Return a resource or an object to intercat with a persistent storage.
     *
     * @return object The specific storage resource or the object to interact with the persistent storage.
     */
    public function getResource(): object;
}
