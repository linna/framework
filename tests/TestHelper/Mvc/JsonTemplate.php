<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\TemplateInterface;

/**
 * Json Template.
 */
class JsonTemplate implements TemplateInterface
{
    public $data;

    public function __construct()
    {
        $this->data = null;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getOutput(): string
    {
        return \json_encode($this->data);
    }
}
