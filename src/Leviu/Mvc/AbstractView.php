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

    /**
     * Constructor.
     * 
     */
    public function __construct()
    {

    }
    
    /**
     * Render template
     * 
     * @param \Leviu\Mvc\TemplateInterface $template
     */
    public function render(TemplateInterface $template)
    {
        
        $template->data = $this->data;
        $template->output();
 
    }
}
