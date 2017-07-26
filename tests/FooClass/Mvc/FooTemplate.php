<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Foo\Mvc;

use Linna\Mvc\TemplateInterface;

class FooTemplate implements TemplateInterface
{
    public $data = null;

    public function setData(array $data)
    {
        $this->data = (object) $data;
    }

    public function getOutput() : string
    {
        return json_encode($this->data);
    }
}
