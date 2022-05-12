<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\Model;

class BeforeAfterModel extends Model
{
    protected $value = -5;

    public function __construct()
    {
        parent::__construct();
    }

    public function before()
    {
        $this->value = 0;
    }

    public function sub()
    {
        $this->value -= 5;
    }

    public function doAction($param)
    {
        $this->value += $param;
    }

    public function add()
    {
        $this->value += 10;
    }

    public function end()
    {
        $this->set(['result' => $this->value]);
    }
}
