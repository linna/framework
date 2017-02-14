<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Mvc;

/**
 * Parent class for model classes.
 *
 * This class was implemented like part of Observer pattern
 * https://en.wikipedia.org/wiki/Observer_pattern
 * http://php.net/manual/en/class.splsubject.php
 */
class Model implements \SplSubject
{
    /**
     * @var object List of attached observerer
     */
    private $observers;

    /**
     * @var array Data for notify to observerer
     */
    public $getUpdate = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * Attach an Observer class to this Subject for updates
     * when occour a subject state change.
     *
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        if ($observer instanceof View) {
            $this->observers->attach($observer);
        }
    }

    /**
     * Detach an Observer class from this Subject.
     *
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        if ($observer instanceof View) {
            $this->observers->detach($observer);
        }
    }

    /**
     * Notify a state change of Subject to all registered Observeres.
     */
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }
}
