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
 * Abstract Connector.
 */
class AbstractConnector
{
    /**
     * @var array<mixed> Connection options
     */
    protected array $options = [];

    /**
     * Constructor.
     *
     * @param array<mixed> $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }
}
