<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Mvc;

use SplObserver;
use SplObjectStorage;
use SplSubject;

/**
 * Parent class for model classes.
 *
 * This class was implemented like part of Observer pattern
 * https://en.wikipedia.org/wiki/Observer_pattern
 * http://php.net/manual/en/class.splsubject.php
 */
class Model implements SplSubject
{
    /**
     * @var SplObjectStorage List of attached observerer
     */
    private SplObjectStorage $observers;

    /**
     * @var array<mixed> Data for notify to observerer
     */
    private array $updates = [];

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    /**
     * Attach an Observer class to this Subject for updates
     * when occour a subject state change.
     *
     * @param SplObserver $observer
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
     * Detach an Observer class from this Subject.
     *
     * @param SplObserver $observer
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
     * Notify a state change of Subject to all registered Observeres.
     *
     * @return void
     */
    public function notify(): void
    {
        /** @var View $value Attached observers. */
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }

    /**
     * Set the data to notify to all registered Observeres.
     *
     * @param array<mixed> $data
     */
    public function set(array $data): void
    {
        $this->updates = \array_merge/*_recursive*/($this->updates, $data);
    }

    /**
     * Get the data to notify to all registered Observeres.
     *
     * @return array<mixed>
     */
    public function get(): array
    {
        return $this->updates;
    }
}
