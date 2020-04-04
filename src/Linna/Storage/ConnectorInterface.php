<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

/**
 * Storage Interface.
 */
interface ConnectorInterface
{
    /**
     * Constructor.
     *
     * @param array<mixed> $options Connection options
     */
    public function __construct(array $options);

    /**
     * Return resource to Database class.
     *
     * @return object
     */
    public function getResource(): object;
}
