<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

use UnexpectedValueException;
use DateTimeImmutable;

/**
 * Provide methods and properties for track the creation and updates of the domain objects.
 */
trait DomainObjectTimeTrait
{
    /** @var DateTimeImmutable|null Insertion date on persistent storage. */
    public ?DateTimeImmutable $created = null;

    /** @var DateTimeImmutable|null Last update date on persistento storage. */
    public ?DateTimeImmutable $lastUpdate = null;

    /**
     * Set the creation date for the object in persistent storage.
     *
     * @return void
     *
     * @throws UnexpectedValueException If the creation date on the object is already set.
     */
    public function setCreated(): void
    {
        if ($this->created !== null) {
            throw new UnexpectedValueException('Creation date property is immutable.');
        }

        $this->created = new DateTimeImmutable(\date(DATE_ATOM));
    }

    /**
     * Set the time for the last object changes in persistent storage.
     *
     * @return void
     */
    public function setLastUpdate(): void
    {
        $this->lastUpdate = new DateTimeImmutable(\date(DATE_ATOM));
    }
}
