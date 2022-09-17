<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Mvc;

use Linna\Mvc\TemplateInterface;

use SplObserver;
use SplSubject;

/**
 * Parent class for view classes.
 *
 * This class was implemented like part of Observer pattern
 * https://en.wikipedia.org/wiki/Observer_pattern
 * http://php.net/manual/en/class.splobserver.php
 */
class View implements SplObserver
{
    /** @var array<mixed> Data for the dynamic view */
    protected array $data = [];

    /**
     * Class Constructor.
     *
     * @param TemplateInterface $template
     */
    public function __construct(protected TemplateInterface $template)
    {
    }

    /**
     * Render a template.
     *
     * @return string
     */
    public function render(): string
    {
        $this->template->setData($this->data);

        return $this->template->getOutput();
    }

    /**
     * Update Observer data.
     *
     * @param SplSubject $subject
     *
     * @return void
     */
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof Model) {
            $this->data = \array_merge($this->data, $subject->get());
        }
    }
}
