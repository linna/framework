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
     * Data for template.
     *
     * @param array<mixed> $data
     *
     * @return void
     */
    public function setData(array $data);

    /**
     * Return output for specific template.
     *
     * @return string
     */
    public function getOutput(): string;
}
