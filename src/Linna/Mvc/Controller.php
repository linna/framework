<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Mvc;

/**
 * The Controller in Model View Controller pattern.
 *
 * <p>This is the parent class for every controller in the applications.</p>
 *
 * <p>To set actions which will be executed before and after the concrete controller, declare inside it all methods
 * needed using as name 'before' and/or 'after'.</p>
 *
 * <p>To set actions which will be executed before and after the entry point or a specific concrete controller action,
 * declare all method needed using as name the name of the method prefixed by the word before or after (ex. for the
 * entryPoint, 'beforeEntryPoint' and 'afterEntryPoint').</p>
 */
abstract class Controller
{
    /**
     * Class Constructor.
     *
     * @param Model $model The model managed by the concrete controller.
     */
    public function __construct(protected Model $model)
    {
    }
}
