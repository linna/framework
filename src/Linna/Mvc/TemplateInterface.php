<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
     * @param array $data
     */
    public function setData(array $data);

    /**
     * Return output for specific template.
     */
    public function getOutput() : string;
}
