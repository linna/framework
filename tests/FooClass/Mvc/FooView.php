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

use Linna\Mvc\View;

class FooView extends View
{
    protected $template;

    public function __construct(FOOModel $model, FOOTemplate $htmlTemplate)
    {
        parent::__construct($model);

        $this->template = $htmlTemplate;
    }

    public function index()
    {
        //$this->template = $this->htmlTemplate;
    }

    public function modifyData()
    {
        //$this->template = $this->htmlTemplate;
    }

    public function modifyDataTimed()
    {
        //$this->template = $this->htmlTemplate;
    }

    public function modifyDataFromParam()
    {
        //$this->template = $this->htmlTemplate;
    }
}
