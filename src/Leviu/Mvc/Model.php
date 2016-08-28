<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Mvc;

/**
 * Parent class for model classes.
 * 
 */
class Model implements \SplSubject
{
    private $observers;
    
    public $getUpdate;
    
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }
    
    public function attach(\SplObserver $observer)
    {   
        if ($observer instanceof View)
        {
            $this->observers->attach($observer);
        }
    }
    
    public function detach(\SplObserver $observer)
    {
        if ($observer instanceof View)
        {
            $this->observers->detach($observer);
        }
    }
    
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }
}
