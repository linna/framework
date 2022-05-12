<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

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
