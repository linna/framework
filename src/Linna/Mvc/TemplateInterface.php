<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Mvc;

/**
 * Template interface.
 */
interface TemplateInterface
{
    /**
     * Set the data used inside the template.
     *
     * @param array<mixed> $data The data used.
     *
     * @return void
     */
    public function setData(array $data);

    /**
     * Return output for specific template, where output means the data formatted using the template.
     *
     * @return string The data which template have been applied.
     */
    public function getOutput(): string;
}
