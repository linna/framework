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
    /**
     * @var array<mixed> Data for the dynamic view
     */
    protected array $data = [];

    /**
     * @var TemplateInterface Template utilized for render data
     */
    protected TemplateInterface $template;

    /**
     * @var Model Model for access data
     */
    protected Model $model;

    /**
     * Class Constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model, TemplateInterface $template)
    {
        $this->model = $model;
        $this->template = $template;
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
