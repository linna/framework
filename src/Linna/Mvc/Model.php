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

use SplObserver;
use SplObjectStorage;
use SplSubject;

/**
 * Parent class for all model classes.
 *
 * <p>This class was implemented like part of Observer pattern.</p>
 *
 * @link https://en.wikipedia.org/wiki/Observer_pattern
 * @link http://php.net/manual/en/class.splsubject.php
 */
abstract class Model implements SplSubject
{
    /** @var SplObjectStorage<SplObserver, View> List of attached observers. */
    private SplObjectStorage $observers;

    /** @var array<mixed> Data which will be notified to observers. */
    private array $updates = [];

    /**
     * Class Constructor.
     *
     * <p>In classes which implements this abstract class, call the parent constructor is mandatory.</p>
     */
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    /**
     * Attach an observer class to this subject, when occour a subject state change all updates will be sent to all
     * observers.
     *
     * @param SplObserver $observer The new observer will be attached.
     *
     * @return void
     */
    public function attach(SplObserver $observer): void
    {
        if ($observer instanceof View) {
            $this->observers->attach($observer);
        }
    }

    /**
     * Detach an observer class from this subject.
     *
     * @param SplObserver $observer The observer will be removed from observers list.
     *
     * @return void
     */
    public function detach(SplObserver $observer): void
    {
        if ($observer instanceof View) {
            $this->observers->detach($observer);
        }
    }

    /**
     * Notify a state change of the subject to all registered observers.
     *
     * @return void
     */
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Set the data to notify to all registered observers.
     *
     * @param array<mixed> $data The data will be notified.
     */
    public function set(array $data): void
    {
        $this->updates = \array_merge($this->updates, $data);
    }

    /**
     * Get the data to notify to all registered observers.
     *
     * @return array<mixed> The data of the subject which will be used by the observer.
     */
    public function get(): array
    {
        return $this->updates;
    }
}
