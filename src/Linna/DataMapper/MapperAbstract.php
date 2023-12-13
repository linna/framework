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

use InvalidArgumentException;

/**
 * Abstract Class for ObjectMapper.
 *
 * <p>Contains common methods for all mappers.</p>
 */
abstract class MapperAbstract
{
    /**
     * Create a new instance of the domain object for which this mapper is
     * responsible for.
     *
     * @return DomainObjectInterface The new instance of the domain object.
     */
    public function create(): DomainObjectInterface
    {
        return $this->concreteCreate();
    }

    /**
     * Insert the instance of the domain object in persistent storage, updates the stored domain object if already
     * exists.
     *
     * @param DomainObjectInterface $domainObject The domain object which will be stored or updated.
     *
     * @return void
     */
    public function save(DomainObjectInterface &$domainObject): void
    {
        if ($domainObject->hasNotId()) {
            $this->concreteInsert($domainObject);
            return;
        }

        $this->concreteUpdate($domainObject);
    }

    /**
     * Delete the domain object from persistent storage.
     *
     * @param DomainObjectInterface $domainObject The domain object which will be deleted.
     *
     * @return void
     */
    public function delete(DomainObjectInterface &$domainObject): void
    {
        $this->concreteDelete($domainObject);
    }

    /**
     * Create a new instance of the domain object for which this mapper is responsible for.
     *
     * Concrete implementation delegated to the concrete mapper.
     *
     * @return DomainObjectInterface The new instance of the domain object.
     */
    abstract protected function concreteCreate(): DomainObjectInterface;

    /**
     * Insert the instance of the domain object in persistent storage.
     *
     * <p>Concrete implementation delegated to the concrete mapper.</p>
     *
     * @param DomainObjectInterface $domainObject The domain object which will be inserted in persistent storage.
     *
     * @return void
     */
    abstract protected function concreteInsert(DomainObjectInterface &$domainObject): void;

    /**
     * Updates the stored domain object.
     *
     * <p>Concrete implementation delegated to the concrete mapper.</p>
     *
     * @param DomainObjectInterface $domainObject The domain object which will be updated.
     *
     * @return void
     */
    abstract protected function concreteUpdate(DomainObjectInterface $domainObject): void;

    /**
     * Delete the domain object from persistent storage.
     *
     * <p>Concrete implementation delegated to the concrete mapper.</p>
     *
     * @param DomainObjectInterface $domainObject The domain object which will be deleted.
     *
     * @return void
     */
    abstract protected function concreteDelete(DomainObjectInterface &$domainObject): void;

    /**
     * Check if the domain object that is going to be used by the mapper is of the type required.
     *
     * <p>Concrete implementation delegated to the concrete mapper.</p>
     *
     * @param DomainObjectInterface $domainObject The domain object will be checked.
     *
     * @return void
     *
     * @throws InvalidArgumentException if the domain object isn't of the type required by mapper
     */
    //abstract protected function checkDomainObjectType(DomainObjectInterface $domainObject): void;
}
