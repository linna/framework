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
 * Parent class for model classes.
 * 
 */
class Model implements \SplSubject
{
    private $observers;
    public $getUpdate;
    
    /**
     * @var object Database Connection
     */

    /**
     * Constructor
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }
    
    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }
    
    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }
    
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }
    
    //public function test($test)
    //{
    //    $this->data =
    //}
}
