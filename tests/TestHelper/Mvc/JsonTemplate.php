<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\TemplateInterface;

class JsonTemplate implements TemplateInterface
{
    public $data = null;

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getOutput() : string
    {
        return json_encode($this->data);
    }
}
