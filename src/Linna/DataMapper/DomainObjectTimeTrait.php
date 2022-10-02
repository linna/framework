<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

use UnexpectedValueException;

/**
 * Provide methods and properties for track the creation and updates of the
 * domain objects.
 */
trait DomainObjectTimeTrait
{
    /** @var string Insertion date on persistent storage. */
    public string $created = '';

    /** @var string Last update date on persistento storage. */
    public string $lastUpdate = '';

    /**
     * Set the creation date for the object.
     *
     * @return void
     *
     * @throws UnexpectedValueException If the creation date on the object is already set.
     */
    public function setCreated(): void
    {
        $date = date(DATE_ATOM);

        if ($this->created !== '') {
            throw new UnexpectedValueException('Creation date property is immutable.');
        }

        $this->created = $date;
    }

    /**
     * Set the time for the last object changes.
     *
     * @return void
     */
    public function setLastUpdate(): void
    {
        $date = date(DATE_ATOM);

        $this->lastUpdate = $date;
    }
}
