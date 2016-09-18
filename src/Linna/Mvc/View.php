<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Mvc;

/**
 * Parent class for view classes.
 *
 * This class was implemented like part of Observer pattern
 * https://en.wikipedia.org/wiki/Observer_pattern
 * http://php.net/manual/en/class.splobserver.php
 */
class View implements \SplObserver
{
    
    /**
     * @var array $data Data for the dynamic view
     */
    protected $data;

    /**
     * @var object $template Template utilized for data
     */
    protected $template;
    
    /**
     * @var object $model Model for access data
     */
    protected $model;
    
    /**
     * Constructor.
     *
     */
    public function __construct(Model $model)
    {
        $this->data = array();
        $this->model = $model;
    }
    
    /**
     * Render a template
     *
     */
    public function render()
    {
        $this->template->data = (object) $this->data;
        $this->template->output();
    }
    
    /**
     * Update Observer data
     *
     * @param \SplSubject $subject
     */
    public function update(\SplSubject $subject)
    {
        if ($subject instanceof Model) {
            $this->data = array_merge($this->data, $subject->getUpdate);
        }
    }
}
