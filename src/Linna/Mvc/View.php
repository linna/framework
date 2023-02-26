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

use Linna\Mvc\TemplateInterface;

use SplObserver;
use SplSubject;

/**
 * Parent class for view classes.
 *
 * <p>This class was implemented like part of Observer pattern</p>
 *
 * @link https://en.wikipedia.org/wiki/Observer_pattern
 * @link http://php.net/manual/en/class.splobserver.php
 */
abstract class View implements SplObserver
{
    /** @var array<mixed> Data for the dynamic view */
    protected array $data = [];

    /**
     * Class Constructor.
     *
     * @param TemplateInterface $template The template used by the view to present the data.
     */
    public function __construct(protected TemplateInterface $template)
    {
    }

    /**
     * Render a template using the data received from the subject.
     *
     * @return string The data which the view has been applied to the template.
     */
    public function render(): string
    {
        $this->template->setData($this->data);

        return $this->template->getOutput();
    }

    /**
     * Update observer data.
     *
     * @param SplSubject $subject The subject which triggered the notify.
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
