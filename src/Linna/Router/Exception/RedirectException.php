<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Router\Exception;

use Exception;

/**
 * Redirect Exception.
 * <p>This class should be used to stop execution when a redirection to another route/path is requred.
 * <code>$path</code> in <code>setPath()</code> method, must be a value present in at least one <code>Route</code>
 * object inside routes collection passed to the <code>Router</code> object.</p>
 */
class RedirectException extends Exception
{
    /**
     * Class Constructor.
     *
     * @param string $path The path on which to be redirected.
     */
    public function __construct(
        /** @var string The path on which to be redirected. */
        private string $path = ''
    ) {
        parent::__construct();

        $this->path = $path;
    }

    /**
     * Set path.
     *
     * @param string $path The path on which to be redirected.
     *
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Get path.
     *
     * @return string The path on which to be redirected.
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
