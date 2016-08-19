<?php

/**
 * Leviu.
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
namespace Leviu\Mvc;

/**
 * Abstract class for views
 * 
 */
abstract class AbstractView
{
    
    /**
     * 
     * @var objec Data for the dynamic view
     */
    protected $data;

    protected $template;
    
    protected $model;
    /**
     * Constructor.
     * 
     */
    public function __construct($model)
    {
        //$this->data = (object) null;
        $this->model = $model;
    }
    
    /**
     * Render template
     * 
     * @param \Leviu\Mvc\TemplateInterface $template
     */
    public function render()
    {
        $this->template->data = $this->data;
        $this->template->output();
    }
}
