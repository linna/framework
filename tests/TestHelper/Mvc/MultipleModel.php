<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\Model;

class MultipleModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function SomeParam($year, $month, $day)
    {
        $this->set(['result' => date('Y-m-d H:i:s', mktime(12, 0, 0, $month, $day, $year))]);
    }
}
